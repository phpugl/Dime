<?php

namespace Dime\TimetrackerBundle\Controller;

use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations as FOS;
use Doctrine\ORM\NoResultException;

use Dime\TimetrackerBundle\Entity\Activity;
use Dime\TimetrackerBundle\Entity\ActivityRepository;
use Dime\TimetrackerBundle\Entity\Timeslice;
use Dime\TimetrackerBundle\Entity\CustomerRepository;
use Dime\TimetrackerBundle\Entity\ProjectRepository;
use Dime\TimetrackerBundle\Entity\ServiceRepository;
use Dime\TimetrackerBundle\Entity\Tag;
use Dime\TimetrackerBundle\Entity\TagRepository;
use Dime\TimetrackerBundle\Form\ActivityType;

class ActivitiesController extends DimeController
{
    /**
     * @var array allowed filter keys
     */
    protected $allowed_filter = array(
        'date',
        'active',
        'customer',
        'project',
        'service',
        'user',
        'withTags',
        'withoutTags'
    );

    /**
     * get activity repository
     *
     * @return ActivityRepository
     */
    protected function getActivityRepository()
    {
        return $this->getDoctrine()->getRepository('DimeTimetrackerBundle:Activity');
    }

    /**
     * get customer repository
     *
     * @return CustomerRepository
     */
    protected function getCustomerRepository()
    {
        return $this->getDoctrine()->getRepository('DimeTimetrackerBundle:Customer');
    }

    /**
     * get project repository
     *
     * @return ProjectRepository
     */
    protected function getProjectRepository()
    {
        return $this->getDoctrine()->getRepository('DimeTimetrackerBundle:Project');
    }



    /**
     * get activity repository
     *
     * @return ServiceRepository
     */
    protected function getServiceRepository()
    {
        return $this->getDoctrine()->getRepository('DimeTimetrackerBundle:Service');
    }

    /**
     * get a list of all activities
     *
     * [GET] /activities
     *
     * @FOS\Route("/activities")
     * @return View
     */
    public function getActivitiesAction()
    {
        $activities = $this->getActivityRepository();

        $activities->createCurrentQueryBuilder('a');

        // Filter
        $filter = $this->getRequest()->get('filter');
        if ($filter) {
            $activities->filter($this->cleanFilter($filter, $this->allowed_filter));
        }

        // Scope by current user
        if (!isset($filter['user'])) {
            $activities->scopeByField('user', $this->getCurrentUser()->getId());
        }

        // Sort by updatedAt and id
        $activities->getCurrentQueryBuilder()->addOrderBy('a.updatedAt', 'DESC');
        $activities->getCurrentQueryBuilder()->addOrderBy('a.id', 'DESC');

        // Pagination
        return $this->paginate(
            $activities->getCurrentQueryBuilder(),
            $this->getRequest()->get('limit'),
            $this->getRequest()->get('offset')
        );
    }

    /**
     * get an activity by its id
     *
     * [GET] /activities/{id}
     *
     * @param  int  $id
     * @return View
     */
    public function getActivityAction($id)
    {
        // find activity
        $activity = $this->getActivityRepository()->find($id);

        // check if it exists
        if ($activity) {
            // send array
            $view = $this->createView($activity);
        } else {
            // activity does not exists send 404
            $view = $this->createView("Activity does not exist.", 404);
        }

        return $view;
    }

    /**
     * create a new activity
     *
     * [POST] /activities
     *
     * @return View
     */
    public function postActivitiesAction()
    {
        // create new activity entity
        $activity = new Activity();

        // convert json to assoc array from request content
        $data = json_decode($this->getRequest()->getContent(), true);

        if (isset($data['parse'])) {
            // Run parser
            $result = $this->parse($data['parse']);
            if (isset($data['date'])) {
                $date = new \DateTime($data['date']);
            } else {
                $date = new \DateTime();
            }

            // create new activity and timeslice entity
            $activity = new Activity();
            $activity->setUser($this->getCurrentUser());

            if (isset($result['customer'])) {
                try {
                    $customer = $this->getCustomerRepository()
                        ->createCurrentQueryBuilder('c')
                        ->scopeByField('user', $this->getCurrentUser()->getId())
                        ->scopeByField('alias', $result['customer'])
                        ->getCurrentQueryBuilder()
                        ->setMaxResults(1)
                        ->getQuery()->getSingleResult();

                    $activity->setCustomer($customer);
                } catch (NoResultException $e) {
                }
            }

            if (isset($result['project'])) {
                try {
                    $project = $this->getProjectRepository()
                        ->createCurrentQueryBuilder('p')
                        ->scopeByField('user', $this->getCurrentUser()->getId())
                        ->scopeByField('alias', $result['project'])
                        ->getCurrentQueryBuilder()
                        ->setMaxResults(1)
                        ->getQuery()->getSingleResult();
                    $activity->setProject($project);
                    // Auto set customer because of direct relation to project
                    if ($activity->getCustomer() == null) {
                        $activity->setCustomer($project->getCustomer());
                    }
                } catch (NoResultException $e) {
                }
            }

            if (isset($result['service'])) {
                try {
                    $service = $this->getServiceRepository()
                        ->createCurrentQueryBuilder('p')
                        ->scopeByField('user', $this->getCurrentUser()->getId())
                        ->scopeByField('alias', $result['service'])
                        ->getCurrentQueryBuilder()
                        ->setMaxResults(1)
                        ->getQuery()->getSingleResult();
                    $activity->setService($service);
                } catch (NoResultException $e) {
                }
            }

            if (isset($result['tags']) && !empty($result['tags'])) {
                foreach ($result['tags'] as $tagname) {

                    try {
                        $tag = $this->getTagRepository()
                            ->createCurrentQueryBuilder('t')
                            ->scopeByField('user', $this->getCurrentUser()->getId())
                            ->scopeByField('name', $tagname)
                            ->getCurrentQueryBuilder()
                            ->setMaxResults(1)
                            ->getQuery()->getSingleResult();
                    } catch (NoResultException $e) {
                        $tag = null;
                    }

                    if ($tag == null) {
                        $tag = new Tag();
                        $tag->setName($tagname);
                        $tag->setUser($this->getCurrentUser());
                    }
                    $activity->addTag($tag);
                }
            }

            if (isset($result['description'])) {
                $activity->setDescription($result['description']);
            }

            // create timeslice
            $timeslice = new Timeslice();
            $timeslice->setActivity($activity);
            $timeslice->setUser($this->getCurrentUser());
            $activity->addTimeslice($timeslice);
            if (isset($result['range']) || isset($result['duration'])) {
                // process time range
                if (isset($result['range'])) {
                    $range = $result['range'];

                    if (empty($range['stop'])) {
                        $start = new \DateTime($range['start']);
                        $stop = new \DateTime('now');
                    } elseif (empty($range['start'])) {
                        $start = new \DateTime('now');
                        $stop = new \DateTime($range['stop']);
                    } elseif (!empty($range['start']) && !empty($range['stop'])) {
                        $start = new \DateTime($range['start']);
                        $stop = new \DateTime($range['stop']);
                    }
                    $start->setDate($date->format('Y'), $date->format('m'), $date->format('d'));
                    $stop->setDate($date->format('Y'), $date->format('m'), $date->format('d'));

                    $timeslice->setStartedAt($start);
                    $timeslice->setStoppedAt($stop);
                } else {
                    // track date for duration
                    $date->setTime(0, 0, 0);
                    $timeslice->setStartedAt($date);
                    $timeslice->setStoppedAt($date);
                }

                // process duration
                if (isset($result['duration'])) {
                    if (empty($result['duration']['sign'])) {
                        $timeslice->setDuration($result['duration']['number']);
                    } else {
                        if ($result['duration']['sign'] == '-') {
                            $timeslice->setDuration($timeslice->getCurrentDuration() - $result['duration']['number']);
                        } else {
                            $timeslice->setDuration($timeslice->getCurrentDuration() + $result['duration']['number']);
                        }
                    }
                }
            } else {
                // start a new timeslice with date 'now'
                $timeslice->setStartedAt(new \DateTime('now'));
            }

            // save change to database
            $em = $this->getDoctrine()->getManager();
            $em->persist($activity);
            $em->flush();
            $em->refresh($activity);

            $view = $this->createView($activity);
        } else {
            // create activity form
            $form = $this->createForm(new ActivityType($this->getDoctrine()->getManager(), $this->getCurrentUser()), $activity);
            $view = $this->saveForm($form, $data);
        }

        return $view;
    }

    /**
     * modify an activity by its id
     *
     * [PUT] /activities/{id}
     *
     * @param  string $id
     * @return View
     */
    public function putActivitiesAction($id)
    {
        // find activity
        $activity = $this->getActivityRepository()->find($id);

        // check if it exists
        if ($activity) {
            $data = json_decode($this->getRequest()->getContent(), true);

            // create form, decode request and save it if valid
            $view = $this->saveForm(
                $this->createForm(new ActivityType($this->getDoctrine()->getManager(), $this->getCurrentUser()), $activity),
                $data
            );
        } else {
            // activity does not exists send 404
            $view = $this->createView("Activity does not exist.", 404);
        }

        return $view;
    }

    /**
     * delete an activity by its id
     * [DELETE] /activities/{id}
     *
     * @param  int  $id
     * @return View
     */
    public function deleteActivitiesAction($id)
    {
        // find activity
        $activity = $this->getActivityRepository()->find($id);

        // check if it exists
        if ($activity) {
            // remove service
            $em = $this->getDoctrine()->getManager();
            $em->remove($activity);
            $em->flush();

            // send status message
            $view = $this->createView("Activity has been removed.");
        } else {
            // activity does not exists send 404
            $view = $this->createView("Activity does not exist.", 404);
        }

        return $view;
    }

    /**
     * Parse data and create an array output
     * @param  string $data
     * @return array
     */
    protected function parse($data)
    {
        $result = array();
        $parsers = array(
            '\Dime\TimetrackerBundle\Parser\TimerangeParser',
            '\Dime\TimetrackerBundle\Parser\DurationParser',
            '\Dime\TimetrackerBundle\Parser\ActivityRelationParser',
            '\Dime\TimetrackerBundle\Parser\ActivityDescriptionParser'
        );

        foreach ($parsers as $parser) {
            $p = new $parser();
            $result = $p->setResult($result)->run($data);
            $data = $p->clean($data);
            unset($p);
        }

        return $result;
    }
}

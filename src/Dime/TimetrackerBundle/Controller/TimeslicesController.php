<?php

namespace Dime\TimetrackerBundle\Controller;

use Dime\TimetrackerBundle\Entity\Timeslice;
use Dime\TimetrackerBundle\Entity\TimesliceRepository;
use Dime\TimetrackerBundle\Form\TimesliceType;
use FOS\RestBundle\View\View;

class TimeslicesController extends DimeController
{
    /**
     * @var array allowed filter keys
     */
    protected $allowed_filter = array(
        'date',
        'activity',
        'customer',
        'project',
        'service',
        'user',
        'withTags',
        'withoutTags'
    );

    /**
     * get activity timeslice repository
     *
     * @return TimesliceRepository
     */
    protected function getTimesliceRepository()
    {
        return $this->getDoctrine()->getRepository('DimeTimetrackerBundle:Timeslice');
    }

    /**
     * get tag repository
     *
     * @return TagRepository
     */
    protected function getTagRepository()
    {
        return $this->getDoctrine()->getRepository('DimeTimetrackerBundle:Tag');
    }

    /**
     * get a list of all timeslices
     *
     * [GET] /timeslices
     *
     * @return View
     */
    public function getTimeslicesAction()
    {
        $timeslices = $this->getTimesliceRepository();

        $timeslices->createCurrentQueryBuilder('ts');

        // Filter
        $filter = $this->getRequest()->get('filter');
        if ($filter)  {
            $timeslices->filter($this->cleanFilter($filter, $this->allowed_filter));
        }

        // Scope by current user
        if (!isset($filter['user'])) {
            $timeslices->scopeByUser($this->getCurrentUser()->getId());
        }

        // Sort by updatedAt
        $timeslices->getCurrentQueryBuilder()->addOrderBy('ts.updatedAt', 'DESC');

        // Pagination
        return $this->paginate($timeslices->getCurrentQueryBuilder(),
            $this->getRequest()->get('limit'),
            $this->getRequest()->get('offset')
        );
    }

    /**
     * get an timeslice by its id
     *
     * [GET] /timeslices/{id}
     *
     * @param  int  $id
     * @return View
     */
    public function getTimesliceAction($id)
    {
        // find activity TimeSlice
        $timeslice = $this->getTimesliceRepository()->find($id);

        // check if it exists
        if ($timeslice) {
            // send array
            $view = $this->createView($timeslice);
        } else {
            // activity TimeSlice does not exists send 404
            $view = $this->createView("Timeslice does not exist.", 404);
        }

        return $view;
    }

    /**
     * create a new timeslice
     *
     * [POST] /timeslices
     *
     * @return View
     */
    public function postTimeslicesAction()
    {
        // create new activity entity
        $timeslice = new Timeslice();

        // create activity form
        $form = $this->createForm(new TimesliceType($this->getDoctrine()->getManager(), $this->getCurrentUser()), $timeslice);

        // convert json to assoc array from request content
        $data = json_decode($this->getRequest()->getContent(), true);

        // parse duration
        $data = $this->process($data);

        return $this->saveForm($form, $data);
    }

    /**
     * modify a timeslice by its id
     *
     * [PUT] /timeslices/{id}
     *
     * @param  string $id
     * @return View
     */
    public function putTimesliceAction($id)
    {
        // find activity
        $timeslice = $this->getTimesliceRepository()->find($id);

        // check if it exists
        if ($timeslice) {
            // convert json to assoc array from request content
            $data = json_decode($this->getRequest()->getContent(), true);

            // parse duration
            $data = $this->process($data);

            // create form, decode request and save it if valid
            $view = $this->saveForm(
                $this->createForm(new TimesliceType($this->getDoctrine()->getManager(), $this->getCurrentUser()), $timeslice), $data
            );
        } else {
            // activity does not exists send 404
            $view = $this->createView("Timeslice does not exist.", 404);
        }

        return $view;
    }

    /**
     * delete a timeslice by its id
     * [DELETE] /timeslices/{id}
     *
     * @param  int  $id
     * @return View
     */
    public function deleteTimesliceAction($id)
    {
        // find activity
        $timeslice = $this->getTimesliceRepository()->find($id);

        // check if it exists
        if ($timeslice) {
            // remove service
            $em = $this->getDoctrine()->getManager();
            $em->remove($timeslice);
            $em->flush();

            // send status message
            $view = $this->createView("Timeslice has been removed.");
        } else {
            // activity does not exists send 404
            $view = $this->createView("Timeslice does not exist.", 404);
        }

        return $view;
    }

    protected function process(array $data)
    {
      if (isset($data['formatDuration'])) {
        $parser = new \Dime\TimetrackerBundle\Parser\DurationParser();

        $result = $parser->run($data['formatDuration']);

        if (!empty($result)) {
          $data['duration'] = $result['duration']['number'];
        }
      }

      if (isset($data['startedAt-date'])) {

      }

      if (isset($data['stoppedAt-date'])) {

      }

      return $data;
    }
}

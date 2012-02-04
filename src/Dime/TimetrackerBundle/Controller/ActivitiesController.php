<?php

namespace Dime\TimetrackerBundle\Controller;

use FOS\RestBundle\View\View;
use Dime\TimetrackerBundle\Entity\Activity;
use Dime\TimetrackerBundle\Form\ActivityType;

class ActivitiesController extends DimeController
{
    /**
     * get activity repository
     *
     * @return Dime\TimetrackerBundle\Entity\ActivityRepository
     */
    protected function getActivityRepository()
    {
        return $this->getDoctrine()->getRepository('DimeTimetrackerBundle:Activity');
    }

    /**
     * get a list of all activities
     *
     * [GET] /activities
     *
     * @return FOS\RestBundle\View\View
     */
    public function getActivitiesAction()
    {
        $activities = $this->getActivityRepository();
        $view = View::create()->setData($activities->findAll());

        return $this->get('fos_rest.view_handler')->handle($view);
    }

    /**
     * get an activity by its id
     *
     * [GET] /activities/{id}
     *
     * @param int $id
     * @return FOS\RestBundle\View\View
     */
    public function getActivityAction($id)
    {
        // find activity
        $activity = $this->getActivityRepository()->find($id);

        // check if it exists
        if ($activity) {
            // send array
            $view = View::create()->setData($activity);
        } else {
            // activity does not exists send 404
            $view = View::create()->setStatusCode(404);
            $view->setData("Activity does not exist.");
        }
        return $this->get('fos_rest.view_handler')->handle($view);
    }

    /**
     * create a new activity
     *
     * [POST] /activities
     *
     * @return FOS\RestBundle\View\View
     */
    public function postActivitiesAction()
    {
        // create new activity entity
        $activity = new Activity();

        // create activity form
        $form = $this->createForm(new ActivityType(), $activity);

        // convert json to assoc array from request content
        $data = json_decode($this->getRequest()->getContent(), true);

        return $this->get('fos_rest.view_handler')->handle($this->saveForm($form, $data));
    }

    /**
     * modify an activity by its id
     *
     * [PUT] /activities/{id}
     *
     * @param string $id
     * @return FOS\RestBundle\View\View
     */
    public function putActivityAction($id)
    {
        // find activity
        $activity = $this->getActivityRepository()->find($id);

        // check if it exists
        if ($activity) {
            // create form, decode request and save it if valid
            $view = $this->saveForm(
                $this->createForm(new ActivityType(), $activity),
                json_decode($this->getRequest()->getContent(), true)
            );
        } else {
            // activity does not exists send 404
            $view = View::create()->setStatusCode(404);
            $view->setData("Activity does not exist.");
        }
        return $this->get('fos_rest.view_handler')->handle($view);
    }

    /**
     * delete an activity by its id
     * [DELETE] /activities/{id}
     *
     * @param int $id
     * @return FOS\RestBundle\View\View
     */
    public function deleteActivityAction($id)
    {
        // find activity
        $activity = $this->getActivityRepository()->find($id);

        // check if it exists
        if ($activity) {
            // remove service
            $em = $this->getDoctrine()->getEntityManager();
            $em->remove($activity);
            $em->flush();

            // send status message
            $view = View::create()->setData("Activity has been removed.");
        } else {
            // activity does not exists send 404
            $view = View::create()->setStatusCode(404);
            $view->setData("Activity does not exists.");
        }
        return $this->get('fos_rest.view_handler')->handle($view);
    }
}

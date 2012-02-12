<?php

namespace Dime\TimetrackerBundle\Controller;

use FOS\RestBundle\View\View;
use Dime\TimetrackerBundle\Entity\Timeslice;
use Dime\TimetrackerBundle\Form\TimesliceType;

class TimeslicesController extends DimeController
{
    /**
     * get activity timeslice repository
     *
     * @return Dime\TimetrackerBundle\Entity\TimesliceRepository
     */
    protected function getTimesliceRepository()
    {
        return $this->getDoctrine()->getRepository('DimeTimetrackerBundle:Timeslice');
    }

    /**
     * get a list of all activitiy TimeSlices
     *
     * [GET] /activities/timeslices
     *
     * @return FOS\RestBundle\View\View
     */
    public function getTimeslicesAction()
    {
        $timeslices = $this->getTimesliceRepository();
        $view = View::create()->setData($timeslices->findAll());

        return $this->get('fos_rest.view_handler')->handle($view);
    }

    /**
     * get an activity timeslice by its id
     *
     * [GET] /activities/timeslices/{id}
     *
     * @param int $id
     * @return FOS\RestBundle\View\View
     */
    public function getTimesliceAction($id)
    {
        // find activity TimeSlice
        $timeslice = $this->getTimesliceRepository()->find($id);

        // check if it exists
        if ($timeslice) {
            // send array
            $view = View::create()->setData($timeslice);
        } else {
            // activity TimeSlice does not exists send 404
            $view = View::create()->setStatusCode(404);
            $view->setData("Timeslice does not exist.");
        }
        return $this->get('fos_rest.view_handler')->handle($view);
    }

    /**
     * create a new activity timeslice
     *
     * [POST] /activities/timeslices
     *
     * @return FOS\RestBundle\View\View
     */
    public function postTimeslicesAction()
    {
        // create new activity entity
        $timeslice = new Timeslice();

        // create activity form
        $form = $this->createForm(new TimesliceType(), $timeslice);

        // convert json to assoc array from request content
        $data = json_decode($this->getRequest()->getContent(), true);

        return $this->get('fos_rest.view_handler')->handle($this->saveForm($form, $data));
    }

    /**
     * modify an activity timeslice by its id
     *
     * [PUT] /activities/timeslices/{id}
     *
     * @param string $id
     * @return FOS\RestBundle\View\View
     */
    public function putTimesliceAction($id)
    {
        // find activity
        $timeslice = $this->getTimesliceRepository()->find($id);

        // check if it exists
        if ($timeslice) {
            // create form, decode request and save it if valid
            $view = $this->saveForm(
                $this->createForm(new TimesliceType(), $timeslice),
                json_decode($this->getRequest()->getContent(), true)
            );
        } else {
            // activity does not exists send 404
            $view = View::create()->setStatusCode(404);
            $view->setData("Timeslice does not exist.");
        }
        return $this->get('fos_rest.view_handler')->handle($view);
    }

    /**
     * delete an activity timeslice by its id
     * [DELETE] /activities/timeslices/{id}
     *
     * @param int $id
     * @return FOS\RestBundle\View\View
     */
    public function deleteTimesliceAction($id)
    {
        // find activity
        $timeslice = $this->getTimesliceRepository()->find($id);

        // check if it exists
        if ($timeslice) {
            // remove service
            $em = $this->getDoctrine()->getEntityManager();
            $em->remove($timeslice);
            $em->flush();

            // send status message
            $view = View::create()->setData("Timeslice has been removed.");
        } else {
            // activity does not exists send 404
            $view = View::create()->setStatusCode(404);
            $view->setData("Timeslice does not exists.");
        }
        return $this->get('fos_rest.view_handler')->handle($view);
    }
}

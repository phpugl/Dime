<?php

namespace Dime\TimetrackerBundle\Controller;

use Dime\TimetrackerBundle\Entity\Service;
use Dime\TimetrackerBundle\Entity\ServiceRepository;
use Dime\TimetrackerBundle\Form\ServiceType;
use FOS\RestBundle\View\View;

class ServicesController extends DimeController
{
    /**
     * @var array allowed filter keys
     */
    protected $allowed_filter = array('search', 'user');

    /**
     * get service repository
     *
     * @return ServiceRepository
     */
    protected function getServiceRepository()
    {
        return $this->getDoctrine()->getRepository('DimeTimetrackerBundle:Service');
    }

    /**
     * get a list of all services
     *
     * [GET] /services
     *
     * @return View
     */
    public function getServicesAction()
    {
        $services = $this->getServiceRepository();

        $services->createCurrentQueryBuilder('s');

        // Filter
        $filter = $this->getRequest()->get('filter');
        if ($filter) {
            $services->filter($this->cleanFilter($filter, $this->allowed_filter));
        }

        // Scope by current user
        if (!isset($filter['user'])) {
            $services->scopeByField('user', $this->getCurrentUser()->getId());
        }

        // Sort by name
        $services->getCurrentQueryBuilder()->addOrderBy('s.name', 'ASC');

        // Pagination
        return $this->paginate($services->getCurrentQueryBuilder(),
            $this->getRequest()->get('limit'),
            $this->getRequest()->get('offset')
        );
    }

    /**
     * get a service by its id
     * [GET] /services/{id}
     *
     * @param  int  $id
     * @return View
     */
    public function getServiceAction($id)
    {
        // find service
        $service = $this->getServiceRepository()->find($id);

        // check if it exists
        if ($service) {
            // send array
            $view = $this->createView($service);
        } else {
            // service does not exists send 404
            $view = $this->createView("Service does not exists.", 404);
        }

        return $view;
    }

    /**
     * create a new service
     * [POST] /services
     *
     * @return View
     */
    public function postServicesAction()
    {
        // create new service
        $service = new Service();

        // create service form
        $form = $this->createForm(new ServiceType($this->getDoctrine()->getManager(), $this->getCurrentUser()), $service);

        // convert json to assoc array from request content
        $data = json_decode($this->getRequest()->getContent(), true);

        return $this->saveForm($form, $data);
    }

    /**
     * modify service by its id
     * [PUT] /services/{id}
     *
     * @param  int  $id
     * @return View
     */
    public function putServicesAction($id)
    {
        // find service
        $service = $this->getServiceRepository()->find($id);

        // check if it exists
        if ($service) {
            // create form, decode request and save it if valid
            $data = json_decode($this->getRequest()->getContent(), true);

            $view = $this->saveForm(
                $this->createForm(new ServiceType($this->getDoctrine()->getManager(), $this->getCurrentUser()), $service),
                $data
            );
        } else {
            // service does not exists send 404
            $view = $this->createView("Service does not exists.", 404);
        }

        return $view;
    }

    /**
     * delete service by its id
     * [DELETE] /services/{id}
     *
     * @param  int  $id
     * @return View
     */
    public function deleteServicesAction($id)
    {
        // find service
        $service = $this->getServiceRepository()->find($id);

        // check if it exists
        if ($service) {
            // remove service
            $em = $this->getDoctrine()->getManager();
            $em->remove($service);
            $em->flush();

            // send status message
            $view = $this->createView("Service has been removed.");
        } else {
            // service does not exists send 404
            $view = $this->createView("Service does not exists", 404);
        }

        return $view;
    }
}

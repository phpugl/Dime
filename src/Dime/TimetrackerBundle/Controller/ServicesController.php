<?php

namespace Dime\TimetrackerBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\View\View;
use Dime\TimetrackerBundle\Entity\Service;
use Dime\TimetrackerBundle\Form\ServiceType;
use Dime\TimetrackerBundle\Controller\DimeController;

class ServicesController extends DimeController
{
    /**
     * get service repository 
     * 
     * @return Dime\TimetrackerBundle\Entity\ServiceRepository
     */
    protected function getServiceRepository()
    {
        return $this->getDoctrine()->getRepository('DimeTimetrackerBundle:Service');
    }

    /**
     * [GET] /services
     *
     * @Route("/")
     */
    public function getServicesAction()
    {
        $services = $this->getServiceRepository()->toArray();
        $view = View::create()->setData($services);

        return $this->get('fos_rest.view_handler')->handle($view);
    }

    /**
     * load service
     * [GET] /services/{id}
     *
     * @param int $id
     */
    public function getServiceAction($id)
    {
        $service = $this->getServiceRepository()->find($id);
        if ($service) {
            $view = View::create()->setData($service->toArray());
        } else {
            $view = View::create()->setStatusCode(404);
            $view->setData("Service does not exist.");
        }
        return $this->get('fos_rest.view_handler')->handle($view);
    }

    /**
     * create service
     * [POST] /services
     *
     * @return void
     */
    public function postServicesAction()
    {
        // create new service
        $service = new Service();
        
        // create service form
        $form = $this->createForm(new ServiceType(), $service);
        
        // get request
        $request = $this->getRequest();
    
        // convert json to assoc array
        $data = json_decode($request->getContent(), true);

        return $this->get('fos_rest.view_handler')->handle($this->saveForm($form, $data));
    }

    /**
     * modify service
     * [PUT] /services/{id}
     *
     * @param int $id
     * @return void
     */
    public function putServicesAction($id)
    {
        $service = $this->getServiceRepository()->find($id);
                
        if ($service) {
            $view = $this->saveForm(
                $this->createForm(new ServiceType(), $service),
                json_decode($this->getRequest()->getContent(), true)
            );
        } else {
            $view = View::create()->setStatusCode(404);
            $view->setData("Service does not exists.");
        }
        return $this->get('fos_rest.view_handler')->handle($view);
    }

    /**
     * delete service
     * [DELETE] /services/{id}
     *
     * @param int $id
     * @return void
     */
    public function deleteServicesAction($id)
    {
        $service = $this->getServiceRepository()->find($id);
        if ($service) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->remove($service);
            $em->flush();
            
            $view = View::create()->setData("Service has been removed.");
        } else {
            $view = View::create()->setStatusCode(404);
            $view->setData("Service does not exists.");
        }
        return $this->get('fos_rest.view_handler')->handle($view);
    }
}

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
     * [GET] /services
     *
     * @Route("/")
     */
    public function getServicesAction()
    {
        $services = $this->getDoctrine()->getRepository('DimeTimetrackerBundle:Service')->allToArray();
        $view = View::create()->setStatusCode(200);
        $view->setData($services);

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
        $service = $this->getDoctrine()->getRepository('DimeTimetrackerBundle:Service')->find($id);
        if ($service) {
            $view = View::create()->setStatusCode(200);
            $view->setData($service->toArray());
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
        $service = $this->getDoctrine()->getRepository('DimeTimetrackerBundle:Service')->find($id);
                
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
        $em = $this->getDoctrine()->getEntityManager();
        $service = $em->getRepository('DimeTimetrackerBundle:Service')->find($id);
        
        if ($service) {
            $em->remove($service);
            $em->flush();
            
            $view = View::create()->setStatusCode(200);
            $view->setData("Service has been removed.");
        } else {
            $view = View::create()->setStatusCode(404);
            $view->setData("Service does not exists.");
        }
    }
}

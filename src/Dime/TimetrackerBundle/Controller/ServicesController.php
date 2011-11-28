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
        $em = $this->getDoctrine()->getEntityManager();
        
        $services = $em->getRepository('DimeTimetrackerBundle:Service')->findAll();
        $data = array();
        foreach ($services as $service) {
            $data[] = $service->toJson();
        }
        $view = View::create()->setStatusCode(200);
        $view->setData($data);

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
        $em = $this->getDoctrine()->getEntityManager();
        $service = $em->getRepository('DimeTimetrackerBundle:Service')->find($id);
        if ($service) {
            $view = View::create()->setStatusCode(200);
            $view->setData($service->toJson());
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
                
        // clean array from non existing keys to avoid extra data 
        foreach ($data as $key => $value) {
            if (!$form->has($key)) {
                unset($data[$key]);
            }
        }
        
        // bind data to form
        $form->bind($data);

        return $this->get('fos_rest.view_handler')->handle($this->saveForm($form));
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
        $em = $this->getDoctrine()->getEntityManager();
        $service = $em->getRepository('DimeTimetrackerBundle:Service')->find($id);
                
        if ($service) {
            $request = $this->getRequest();
            
            // convert json to assoc array
            $data = json_decode($request->getContent(), true);
            
            // create service form
            $form = $this->createForm(new ServiceType(), $service);
       
            // clean array from non existing keys to avoid extra data 
            foreach ($data as $key => $value) {
                if (!$form->has($key)) {
                    unset($data[$key]);
                }
            }
            
            // bind data to form
            $form->bind($data);
            
            $view = $this->saveForm($form);
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

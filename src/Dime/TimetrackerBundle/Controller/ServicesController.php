<?php

namespace Dime\TimetrackerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\View\View;
use Dime\TimetrackerBundle\Entity\Service;
use Dime\TimetrackerBundle\Form\ServiceType;


class ServicesController extends Controller
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
            $view->setData("Service does not exists.");
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
        $service = new Service();
        
        // create service form
        $form = $this->createForm(new ServiceType(), $service);
        
        
        $request = $this->getRequest();
    
        // convert json to assoc array
        $data = json_decode($request->getContent(), true);
        
        // bind data to form
        $form->bind($data);

        if ($form->isValid()) {
            // save change to database
            $em->persist($service);
            $em->flush();

            // push back the new object
            $view = View::create()->setStatusCode(200);
            $view->setData($service->toJson());
        } else {
            // return error sting from form
            $view = View::create()->setStatusCode(404);
            $view->setData($form->getErrorsAsString());
        }
        
        return $this->get('fos_rest.view_handler')->handle($view);
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
            
            // clean id from array
            if (isset($data['id'])) {
                unset($data['id']);
            }
            
            // create service form
            $form = $this->createForm(new ServiceType(), $service);
            
            // bind data to form
            $form->bind($data);
            
            // check if is valid
            if ($form->isValid()) {
                // save change to database
                $em->persist($service);
                $em->flush();
                
                // push back the new object
                $view = View::create()->setStatusCode(200);
                $view->setData($service->toJson());
            } else {
                // return error sting from form
                $view = View::create()->setStatusCode(404);
                $view->setData($form->getErrorsAsString());
            }
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

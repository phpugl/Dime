<?php

namespace Dime\TimetrackerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\View\View;

class ServicesController extends Controller
{
    /**
     * [GET] /services
     *
     * @Route("/")
     */
    public function getServicesAction()
    {
        $services = $this->getDoctrine()->getRepository('Dime\TimetrackerBundle\Entity\Service')->findAll();
        $data = array();
        foreach ($services as $service) {
            $data[$service->getId()] = $service->getName();
        }
        $view = View::create()->setStatusCode(200);
        $view->setData($data);
        return $this->get('fos_rest.view_handler')->handle($view);
    }

    /**
     * load service
     * [GET] /services/{slug}
     *
     * @param int $id
     */
    public function getServiceAction($id)
    {
        $service = $this->getDoctrine()->getRepository('Dime\TimetrackerBundle\Entity\Service')->find($id);
        if ($service) {
            $data = array();
            $data['id']          = $service->getId();
            $data['name']        = $service->getName();
            $data['description'] = $service->getDescription();
            $data['rate']        = $service->getRate();
        }
        $view = View::create()->setStatusCode(200);
        $view->setData($data);
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
        $this->persist(new Service());

        return $view;
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
        $service = $this->getDoctrine()->getRepository('Dime\TimetrackerBundle\Entity\Service')->find($id);
        if ($service) {
            $this->persist($service);
        }
        return $view;
    }

    /**
     * persist service
     * 
     * @param Dime\TimetrackerBundle\Entity\Service $service 
     */
    protected function persist(Dime\TimetrackerBundle\Entity\Service $service)
    {
        $form = $this->getForm($service);

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($service);
            $em->flush();
        }
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
        if ($service = $this->getDoctrine()->getRepository('Dime\TimetrackerBundle\Entity\Service')->find($id)) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->remove($service);
        }
    }

    protected function getForm($service)
    {
        return $this->formFactory->createBuilder('form', $service)
            ->add('name',        'string',  array('required' => true))
            ->add('description', 'text',    array('required' => false))
            ->add('rate',        'decimal', array('required' => false))
            ->getForm();
    }
}

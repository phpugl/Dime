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
    {}

    /**
     * create service
     * [POST] /services
     *
     * @return void
     */
    public function postServicesAction()
    {}

    /**
     * modify service
     * [PUT] /services/{id}
     *
     * @param int $id
     * @return void
     */
    public function putServicesAction($id)
    {}

    /**
     * delete service
     * [DELETE] /services/{id}
     *
     * @param int $id
     * @return void
     */
    public function deleteServicesAction($id)
    {}
}

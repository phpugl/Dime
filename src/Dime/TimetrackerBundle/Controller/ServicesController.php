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
        $view = View::create()->setStatusCode(200);
        $view->setData(/* data to send */);
        return $this->get('fos_rest.view_handler')->handle($view);
    }

    /**
     * load service
     *
     * [GET] /services/{slug}
     */
    public function getServiceAction($slug)
    {}

    /**
     * create service
     * [POST] /services
     * 
     * @return void
     */
    public function postServiceAction()
    {}

    /**
     * modify service
     * [PUT] /services/{slug}
     * 
     * @param string $slug 
     * @return void
     */
    public function putServiceAction($slug)
    {}
}

<?php

namespace Dime\TimetrackerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\View\View;

class ServicesController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        $view = View::create()->setStatusCode(200);
        $view->setData(/* data to send */);
        return $this->get('fos_rest.view_handler')->handle($view);
    }
}

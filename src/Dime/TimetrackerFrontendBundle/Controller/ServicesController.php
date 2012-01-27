<?php

namespace Dime\TimetrackerFrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ServicesController extends Controller
{
    /**
     * @Route("/services")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }
}

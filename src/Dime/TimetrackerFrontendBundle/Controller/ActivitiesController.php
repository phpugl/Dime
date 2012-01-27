<?php

namespace Dime\TimetrackerFrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ActivitiesController extends Controller
{
    /**
     * @Route("/activities")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }
}

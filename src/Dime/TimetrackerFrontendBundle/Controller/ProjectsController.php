<?php

namespace Dime\TimetrackerFrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ProjectsController extends Controller
{
    /**
     * @Route("/projects")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }
}

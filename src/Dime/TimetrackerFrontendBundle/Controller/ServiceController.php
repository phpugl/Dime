<?php

namespace Dime\TimetrackerFrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Yaml\Yaml;

class ServiceController extends Controller
{
    /**
     * @Route("/service")
     * @Template()
     */
    public function indexAction()
    {
        return array(
            'services' => $this->getDoctrine()->getRepository('DimeTimetrackerBundle:Service')->findAll()
        );
    }
}

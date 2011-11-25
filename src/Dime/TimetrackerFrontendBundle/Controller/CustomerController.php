<?php

namespace Dime\TimetrackerFrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Yaml\Yaml;

class CustomerController extends Controller
{
    /**
     * @Route("/customer")
     * @Template()
     */
    public function indexAction()
    {
        return array(
            'customers' => array() // TODO $this->getDoctrine()->getRepository('DimeTimetrackerBundle:Customer')->findAll()
        );
    }
}

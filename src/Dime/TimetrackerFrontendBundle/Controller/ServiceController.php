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
        $array = Yaml::parse(file_get_contents(dirname(__FILE__) . '/../Resources/fixtures/data.yml'));

        return array('fixtures' => $array);
    }
}

<?php

namespace Dime\TimetrackerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\View\View;

class ActivitiesController extends Controller
{
    /**
     * @Route("/")
     */
    public function getActivitiesAction()
    {
        $em         = $this->get('doctrine')->getEntityManager();
        $activities = $em->getRepository('DimeTimetrackerBundle:Activity')->findAll();

        $view = View::create()
                  ->setData($activities)
                  ;

        return $this->get('fos_rest.view_handler')->handle($view);
    } //"get_users"    [GET] /users
}

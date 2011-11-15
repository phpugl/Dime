<?php

namespace Dime\TimetrackerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\View\View;

class ActivitiesController extends Controller
{
    /**
     * [GET] /activities
     *
     * @Route("/")
     */
    public function getActivitiesAction()
    {
        $activities = $this->getDoctrine()->getRepository('DimeTimetrackerBundle:Activity')->allToArray();

        $view = View::create()
                  ->setStatusCode(200)
                  ->setData($activities)
                  ;

        return $this->get('fos_rest.view_handler')->handle($view);
    }
}

<?php

namespace Dime\TimetrackerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\View\View;

class ProjectController extends Controller
{
    /**
     * [GET] /projects
     *
     * @Route("/")
     */
    public function getProjectsAction()
    {
        ~
        $services = $this->getDoctrine()
                         ->getRepository('DimeTimetrackerBundle:Service')
                         ->findAll();

        $view = View::create()->setStatusCode(200);
        
        $view->setData(array('foo' => 'text'));
        return $this->get('fos_rest.view_handler')->handle($view);
    }

    /**
     * load project
     *
     * [GET] /project/{slug}
     */
    public function getProjectAction($slug)
    {}

    /**
     * create project
     * [POST] /project
     * 
     * @return void
     */
    public function postProjectAction()
    {}

    /**
     * modify project
     * [PUT] /project/{slug}
     * 
     * @param string $slug 
     * @return void
     */
    public function putProjectAction($slug)
    {}
}

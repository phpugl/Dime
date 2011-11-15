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
    public function getProjectAction($id)
    {
        $project = $this->getDoctrine()->getRepository('Dime\TimetrackerBundle\Entity\Project')->find($id);
        if ($project) {
            $data = array();
            $data['id']          = $project->getId();
            $data['name']        = $project->getName();
            $data['description'] = $project->getDescription();
            $data['rate']        = $project->getRate();
        }
        $view = View::create()->setStatusCode(200);
        $view->setData($data);
        return $this->get('fos_rest.view_handler')->handle($view);
    }

    /**
     * create project
     * [POST] /project
     * 
     * @return void
     */
    public function postProjectAction()
    {
        $project = new Project();
        $form = $this->getForm($project);
        $form->bindRequest($this->getRequest());
        $this->persist($form);
        $view->setData($form->getData());
        return $this->get('fos_rest.view_handler')->handle($view);
    }

    /**
     * modify project
     * [PUT] /project/{slug}
     * 
     * @param string $slug 
     * @return void
     */
    public function putProjectAction($slug)
    {
        $project = $this->getDoctrine()->getRepository('Dime\TimetrackerBundle\Entity\Project')->find($id);
        $form = $this->getForm($project);
        $this->persist($form, $project);
        $view->setData($form->getData());
        return $this->get('fos_rest.view_handler')->handle($view);
    }

    /**
     * delete project
     * [DELETE] /projects/{id}
     *
     * @param int $id
     * @return void
     */
    public function deleteProjectsAction($id)
    {
        if ($project = $this->getDoctrine()->getRepository('Dime\TimetrackerBundle\Entity\Project')->find($id)) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->remove($project);
        }
    }

    /**
     * persist project
     * 
     * @param $form
     * @param Dime\TimetrackerBundle\Entity\project $project
     */
    protected function persist($form, Dime\TimetrackerBundle\Entity\Project $project)
    {
        $form->bindRequest($this->getRequest());
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($project);
            $em->flush();
        }
    }

    protected function getForm($project)
    {
        return $this->formFactory->createBuilder('form', $project)
            ->add('name',        'string',  array('required' => true))
            ->add('description', 'text',    array('required' => false))
            ->add('rate',        'decimal', array('required' => false))
            ->getForm();
    }
}

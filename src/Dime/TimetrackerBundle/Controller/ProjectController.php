<?php

namespace Dime\TimetrackerBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\View\View;
use Dime\TimetrackerBundle\Controller\DimeController;
use Dime\TimetrackerBundle\Entity\Project;
use Dime\TimetrackerBundle\Form\ProjectType;

class ProjectController extends DimeController
{
    /**
     * get project repository
     *
     * @return Dime\TimetrackerBundle\Entity\ProjectRepository
     */
    protected function getProjectRepository()
    {
        return $this->getDoctrine()->getRepository('DimeTimetrackerBundle:Project');
    }

    /**
     * get a list of all projects
     *
     * [GET] /projects
     */
    public function getProjectsAction()
    {
        $projects = $this->getProjectRepository()->toArray();
        $view = View::create()->setData($projects);

        return $this->get('fos_rest.view_handler')->handle($view);
    }

    /**
     * get a project
     * [GET] /projects/{id}
     *
     * @param int id
     */
    public function getProjectAction($id)
    {
        $project = $this->getProjectRepository()->find($id);
        if ($project) {
            $view = View::create()->setData($project->toArray());
        } else {
            $view = View::create()->setStatusCode(404);
            $view->setData("Project does not exist.");
        }
        return $this->get('fos_rest.view_handler')->handle($view);
    }

    /**
     * create a new project
     * [POST] /projects
     *
     * @return void
     */
    public function postProjectsAction()
    {
        // create a new project entity
        $project = new Project();

        // create project form
        $form = $this->createForm(new ProjectType(), $project);

        // get request
        $request = $this->getRequest();

        // decode json
        $data = json_decode($this->getContent(), true);

        // save form and send response
        return $this->get('fos_rest.view_handler')->handle($this->saveForm($form, $data));
    }

    /**
     * modify project
     * [PUT] /projects/{id}
     *
     * @param string $id
     * @return void
     */
    public function putProjectAction($id)
    {
        $project = $this->getProjectRepository()->find($id);

        if ($project) {
            $view = $this->saveForm(
                $this->createForm(new ProjectType(), $project),
                json_decode($this->getRequest()->getContent(), true)
            );
        } else {
            $view = View::create()->setStatusCode(404);
            $view->setData("Project does not exist.");

        }
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
        $project = $this->getProjectRepository()->find($id);
        if ($project) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->remove($project);
            $em->flush();

            $view = View::create()->setData("Project has been removed.");
        } else {
            $view = View::create()->setStatusCode(404);
            $view->setData("Project does not exists.");
        }
        return $this->get('fos_rest.view_handler')->handle($view);
    }
}

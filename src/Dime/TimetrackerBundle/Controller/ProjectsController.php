<?php

namespace Dime\TimetrackerBundle\Controller;

use FOS\RestBundle\View\View;
use Dime\TimetrackerBundle\Entity\Project;
use Dime\TimetrackerBundle\Form\ProjectType;

class ProjectsController extends DimeController
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
     *
     * @return FOS\RestBundle\View\View
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
     * @return FOS\RestBundle\View\View
     */
    public function getProjectAction($id)
    {
        // find project
        $project = $this->getProjectRepository()->find($id);
        
        // check if exists
        if ($project) {
            // send array
            $view = View::create()->setData($project->toArray());
        } else {
            // project does not exists send 404
            $view = View::create()->setStatusCode(404);
            $view->setData("Project does not exist.");
        }
        return $this->get('fos_rest.view_handler')->handle($view);
    }

    /**
     * create a new project
     * [POST] /projects
     *
     * @return FOS\RestBundle\View\View
     */
    public function postProjectsAction()
    {
        // create a new project entity
        $project = new Project();

        // create project form
        $form = $this->createForm(new ProjectType(), $project);

        // decode json to assoc array from request content
        $data = json_decode($this->getRequest()->getContent(), true);
        
        // save form and send response
        return $this->get('fos_rest.view_handler')->handle($this->saveForm($form, $data));
    }

    /**
     * modify project
     * [PUT] /projects/{id}
     *
     * @param string $id
     * @return FOS\RestBundle\View\View
     */
    public function putProjectsAction($id)
    {
        // find project
        $project = $this->getProjectRepository()->find($id);
        
        // check if exists
        if ($project) {
            // create form, decode request and save it if valid
            $view = $this->saveForm(
                $this->createForm(new ProjectType(), $project),
                json_decode($this->getRequest()->getContent(), true)
            );
            
        } else {
            // project does not exists send 404
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
     * @return FOS\RestBundle\View\View
     */
    public function deleteProjectsAction($id)
    {
        // find project
        $project = $this->getProjectRepository()->find($id);
        
        // check if exists
        if ($project) {
            // remove project
            $em = $this->getDoctrine()->getEntityManager();
            $em->remove($project);
            $em->flush();

            // send status message
            $view = View::create()->setData("Project has been removed.");
        } else {
            // project does not exists send 404
            $view = View::create()->setStatusCode(404);
            $view->setData("Project does not exists.");
        }
        return $this->get('fos_rest.view_handler')->handle($view);
    }
}

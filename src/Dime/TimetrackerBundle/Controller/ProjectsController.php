<?php

namespace Dime\TimetrackerBundle\Controller;

use Dime\TimetrackerBundle\Entity\Project;
use Dime\TimetrackerBundle\Entity\ProjectRepository;
use Dime\TimetrackerBundle\Form\ProjectType;
use FOS\RestBundle\View\View;

class ProjectsController extends DimeController
{
    /**
     * @var array allowed filter keys
     */
    protected $allowed_filter = array(
        'customer',
        'withTags',
        'withoutTags',
        'search',
        'user'
    );

    /**
     * get project repository
     *
     * @return ProjectRepository
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
     * @return View
     */
    public function getProjectsAction()
    {
        $projects = $this->getProjectRepository();

        $projects->createCurrentQueryBuilder('p');

        // Filter
        $filter = $this->getRequest()->get('filter');
        if ($filter) {
            $qb = $projects->filter($this->cleanFilter($filter, $this->allowed_filter));
        }

        // Scope by current user
        if (!isset($filter['user'])) {
            $projects->scopeByField('user', $this->getCurrentUser()->getId());
        }

        // Sort by name
        $projects->getCurrentQueryBuilder()->addOrderBy('p.name', 'ASC');

        // Pagination
        return $this->paginate($projects->getCurrentQueryBuilder(),
            $this->getRequest()->get('limit'),
            $this->getRequest()->get('offset')
        );
    }

    /**
     * get a project
     * [GET] /projects/{id}
     *
     * @param  int  $id
     * @return View
     */
    public function getProjectAction($id)
    {
        // find project
        $project = $this->getProjectRepository()->find($id);

        // check if exists
        if ($project) {
            // send array
            $view = $this->createView($project);
        } else {
            // project does not exists send 404
            $view = $this->createView("Project does not exist.", 404);
        }

        return $view;
    }

    /**
     * create a new project
     * [POST] /projects
     *
     * @return View
     */
    public function postProjectsAction()
    {
        // create a new project entity
        $project = new Project();

        // create project form
        $form = $this->createForm(new ProjectType($this->getDoctrine()->getManager(), $this->getCurrentUser()), $project);

        // decode json to assoc array from request content
        $data = json_decode($this->getRequest()->getContent(), true);

        // save form and send response
        return $this->saveForm($form, $data);
    }

    /**
     * modify project
     * [PUT] /projects/{id}
     *
     * @param  int  $id
     * @return View
     */
    public function putProjectsAction($id)
    {
        // find project
        $project = $this->getProjectRepository()->find($id);

        // check if exists
        if ($project) {
            // create form, decode request and save it if valid
            $data = json_decode($this->getRequest()->getContent(), true);

            $view = $this->saveForm(
                $this->createForm(new ProjectType($this->getDoctrine()->getManager(), $this->getCurrentUser()), $project),
                $data
            );
        } else {
            // project does not exists send 404
            $view = $this->createView("Project does not exist.", 404);
        }

        return $view;
    }

    /**
     * delete project
     * [DELETE] /projects/{id}
     *
     * @param  int  $id
     * @return View
     */
    public function deleteProjectsAction($id)
    {
        // find project
        $project = $this->getProjectRepository()->find($id);

        // check if exists
        if ($project) {
            // remove project
            $em = $this->getDoctrine()->getManager();
            $em->remove($project);
            $em->flush();

            // send status message
            $view = $this->createView("Project has been removed.");
        } else {
            // project does not exists send 404
            $view = $this->createView("Project does not exist.", 404);
        }

        return $view;
    }
}

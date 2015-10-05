<?php

namespace Dime\TimetrackerBundle\Controller;

use Dime\TimetrackerBundle\Entity\Tag;
use Dime\TimetrackerBundle\Entity\TagRepository;
use Dime\TimetrackerBundle\Form\TagType;
use FOS\RestBundle\View\View;

class TagsController extends DimeController
{
    /**
     * @var array allowed filter keys
     */
    protected $allowed_filter = array('search');

    /**
     * get tag repository
     *
     * @return TagRepository
     */
    protected function getTagRepository()
    {
        return $this->getDoctrine()->getRepository('DimeTimetrackerBundle:Tag');
    }

    /**
     * get a list of all tags
     *
     * [GET] /tags
     *
     * @return View
     */
    public function getTagsAction()
    {
        $tags = $this->getTagRepository();

        $tags->createCurrentQueryBuilder('tag');

        // Filter
        $filter = $this->getRequest()->get('filter');
        if ($filter) {
            $tags->filter($this->cleanFilter($filter, $this->allowed_filter));
        }

        // Scope by current user
        if (!isset($filter['user'])) {
            $tags->scopeByField('user', $this->getCurrentUser()->getId());
        }

        $tags->getCurrentQueryBuilder()->addOrderBy('tag.name', 'ASC');

        // Pagination
        return $this->paginate($tags->getCurrentQueryBuilder(),
            $this->getRequest()->get('limit'),
            $this->getRequest()->get('offset')
        );
    }

    /**
     * get a tag by its id
     *
     * [GET] /tags/{id}
     *
     * @param  int  $id
     * @return View
     */
    public function getTagAction($id)
    {
        // find tag
        $tag = $this->getTagRepository()->find($id);

        // check if it exists
        if ($tag) {
            // send array
            $view = $this->createView($tag);
        } else {
            // send 404, if tag does not exist
            $view = $this->createView("Tag does not exist.", 404);
        }

        return $view;
    }

    /**
     * create a new tag
     * [POST] /tags
     *
     * @return View
     */
    public function postTagsAction()
    {
        // create new service
        $tag = new Tag();

        // create service form
        $form = $this->createForm(new TagType(), $tag);

        // convert json to assoc array from request content
        $data = json_decode($this->getRequest()->getContent(), true);

        return $this->saveForm($form, $data);
    }

    /**
     * modify tag by its id
     * [PUT] /tags/{id}
     *
     * @param  int  $id
     * @return View
     */
    public function putTagsAction($id)
    {
        // find service
        $tag = $this->getTagRepository()->find($id);

        // check if it exists
        if ($tag) {
            // create form, decode request and save it if valid
            $view = $this->saveForm(
                $this->createForm(new TagType(), $tag),
                json_decode($this->getRequest()->getContent(), true)
            );
        } else {
            // service does not exists send 404
            $view = $this->createView("Tag does not exists.", 404);
        }

        return $view;
    }

    /**
     * delete a tag by its id
     * [DELETE] /tags/{id}
     *
     * @param  int  $id
     * @return View
     */
    public function deleteTagAction($id)
    {
        // find tag
        $tag = $this->getTagRepository()->find($id);

        // check if it exists
        if ($tag) {
            // remove service
            $em = $this->getDoctrine()->getManager();
            $em->remove($tag);
            $em->flush();

            // send status message
            $view = $this->createView("Tag has been removed.");
        } else {
            // send 404, if tag does not exist
            $view = $this->createView("Tag does not exist.", 404);
        }

        return $view;
    }
}

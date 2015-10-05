<?php

namespace Dime\TimetrackerBundle\Controller;

use Dime\TimetrackerBundle\Entity\User;
use Dime\TimetrackerBundle\Entity\UserRepository;
use Dime\TimetrackerBundle\Form\UserType;
use FOS\RestBundle\View\View;

class UsersController extends DimeController
{
    /**
     * @var array allowed filter keys
     */
    protected $allowed_filter = array();

    /**
     * get user repository
     *
     * @return UserRepository
     */
    protected function getUserRepository()
    {
        return $this->getDoctrine()->getRepository('DimeTimetrackerBundle:User');
    }

    /**
     * get a list of all users
     *
     * [GET] /users
     *
     * @return View
     */
    public function getUsersAction()
    {
        $users = $this->getUserRepository();

        $qb = $users->createQueryBuilder('u');

        // Filter
        $filter = $this->getRequest()->get('filter');
        if ($filter) {
            $qb = $users->filter($this->cleanFilter($filter, $this->allowed_filter), $qb);
        }

        // Pagination
        return $this->paginate($qb,
            $this->getRequest()->get('limit'),
            $this->getRequest()->get('offset')
        );
    }

    /**
     * get an user by its id
     *
     * [GET] /users/{id}
     *
     * @param  int  $id
     * @return View
     */
    public function getUserAction($id)
    {
        // find user
        $user = $this->getUserRepository()->find($id);

        // check if it exists
        if ($user) {
            // send array
            $view = $this->createView($user);
        } else {
            // user does not exists send 404
            $view = $this->createView("User does not exist.", 404);
        }

        return $view;
    }

    /**
     * create a new user
     *
     * [POST] /users
     *
     * @return View
     */
    public function postUsersAction()
    {
        // create new user entity
        $user = new User();

        // convert json to assoc array from request content
        $data = json_decode($this->getRequest()->getContent(), true);

        // create user form
        $form = $this->createForm(new UserType(), $user);

        return $this->saveForm($form, $data);
    }

    /**
     * modify an user by its id
     *
     * [PUT] /users/{id}
     *
     * @param  string $id
     * @return View
     */
    public function putUserAction($id)
    {
        // find user
        $user = $this->getUserRepository()->find($id);

        // check if it exists
        if ($user) {
            // create form, decode request and save it if valid
            $view = $this->saveForm(
                $this->createForm(new UserType(), $user),
                json_decode($this->getRequest()->getContent(), true)
            );
        } else {
            // user does not exists send 404
            $view = $this->createView("User does not exist.", 404);
        }

        return $view;
    }

    /**
     * delete an user by its id
     * [DELETE] /users/{id}
     *
     * @param  int  $id
     * @return View
     */
    public function deleteUserAction($id)
    {
        // find user
        $user = $this->getUserRepository()->find($id);

        // check if it exists
        if ($user) {
            // remove service
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();

            // send status message
            $view = $this->createView("User has been removed.");
        } else {
            // user does not exists send 404
            $view = $this->createView("User does not exist.", 404);
        }

        return $view;
    }
}

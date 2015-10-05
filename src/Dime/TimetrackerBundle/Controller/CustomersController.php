<?php

namespace Dime\TimetrackerBundle\Controller;

use Dime\TimetrackerBundle\Entity\Customer;
use Dime\TimetrackerBundle\Entity\CustomerRepository;
use Dime\TimetrackerBundle\Form\CustomerType;
use FOS\RestBundle\View\View;

class CustomersController extends DimeController
{
    /**
     * @var array allowed filter keys
     */
    protected $allowed_filter = array(
        'withTags',
        'withoutTags',
        'search',
        'user'
    );

    /**
     * get customer repository
     *
     * @return CustomerRepository
     */
    public function getCustomerRepository()
    {
        return $this->getDoctrine()->getRepository('DimeTimetrackerBundle:Customer');
    }

    /**
     * get a list with all customers
     *
     * [GET] /customers
     *
     * @return View
     */
    public function getCustomersAction()
    {
        $customers = $this->getCustomerRepository();

        $customers->createCurrentQueryBuilder('c');

        // Filter
        $filter = $this->getRequest()->get('filter');
        if ($filter) {
            $customers->filter($this->cleanFilter($filter, $this->allowed_filter));
        }

        // Scope by current user
        if (!isset($filter['user'])) {
            $customers->scopeByField('user', $this->getCurrentUser()->getId());
        }

        // Sort by name
        $customers->getCurrentQueryBuilder()->addOrderBy('c.name', 'ASC');

        // Pagination
        return $this->paginate($customers->getCurrentQueryBuilder(),
            $this->getRequest()->get('limit'),
            $this->getRequest()->get('offset')
        );
    }

    /**
     * get a customer by its id
     *
     * [GET] /customers/{id}
     *
     * @param  int  $id
     * @return View
     */
    public function getCustomerAction($id)
    {
        // find customer
        $customer = $this->getCustomerRepository()->find($id);

        // check if exists
        if ($customer) {
            // send array
            $view = $this->createView($customer);
        } else {
            // customer does not exists send 404
            $view = $this->createView("Customer does not exist.", 404);
        }

        return $view;
    }

    /**
     * create a new customer
     * [POST] /customers
     *
     * @return View
     */
    public function postCustomersAction()
    {
         // create new customer
        $customer = new Customer();

        // create customer form
        $form = $this->createForm(new CustomerType($this->getDoctrine()->getManager(), $this->getCurrentUser()), $customer);

        // convert json to assoc array from request content
        $data = json_decode($this->getRequest()->getContent(), true);
        //$data = $this->handleTagsInput($data);

        return $this->saveForm($form, $data);
    }

    /**
     * modify a customer by its id
     * [PUT] /customers/{id}
     *
     * @param  int  $id
     * @return View
     */
    public function putCustomersAction($id)
    {
        // find customer
        $customer = $this->getCustomerRepository()->find($id);

        // check if exists
        if ($customer) {
            // create form, decode request and save it if valid
            $data = json_decode($this->getRequest()->getContent(), true);
            //$data = $this->handleTagsInput($data);
            $view = $this->saveForm(
                $this->createForm(new CustomerType($this->getDoctrine()->getManager(), $this->getCurrentUser()), $customer),
                $data
            );
        } else {
            // customer does not exists send 404
             $view = $this->createView("Customer does not exist.", 404);
        }

        return $view;
    }

    /**
     * delete customer by its id
     * [DELETE] /customerd/{id}
     *
     * @param  int  $id
     * @return View
     */
    public function deleteCustomersAction($id)
    {
        // find customer
        $customer = $this->getCustomerRepository()->find($id);

        // check if exists
        if ($customer) {
            // remove customer
            $em = $this->getDoctrine()->getManager();
            $em->remove($customer);
            $em->flush();

            // send status message
            $view = $this->createView("Customer has been removed.");
        } else {
            // customer does not exists send 404
            $view = $this->createView("Customer does not exist.", 404);
        }

        return $view;
    }
}

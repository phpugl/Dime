<?php

namespace Dime\TimetrackerBundle\Controller;

use FOS\RestBundle\View\View;
use Dime\TimetrackerBundle\Entity\Customer;
use Dime\TimetrackerBundle\Form\CustomerType;

class CustomersController extends DimeController
{
    /**
     * get customer repository
     *
     * @return Dime\TimetrackerBundle\Entity\CustomerRepository
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
     * @return FOS\RestBundle\View\View
     */
    public function getCustomersAction()
    {
        $customers = $this->getCustomerRepository()->toArray();
        $view = View::create()->setData($customers);

        return $this->get('fos_rest.view_handler')->handle($view);
    }

    /**
     * get a customer by its id
     *
     * [GET] /customers/{id}
     *
     * @param int $id
     * @return FOS\RestBundle\View\View
     */
    public function getCustomerAction($id)
    {
        // find customer
        $customer = $this->getCustomerRepository()->find($id);

        // check if exists
        if ($customer) {
            // send array
            $view = View::create()->setData($customer->toArray());
        } else {
            // customer does not exists send 404
            $view = View::create()->setStatusCode(404);
            $view->setData("Customer does not exist.");
        }
        return $this->get('fos_rest.view_handler')->handle($view);
    }

    /**
     * create a new customer
     * [POST] /customers
     *
     * @return FOS\RestBundle\View\View
     */
    public function postCustomersAction()
    {
         // create new customer
        $customer = new Customer();

        // create customer form
        $form = $this->createForm(new CustomerType(), $customer);

        // convert json to assoc array from request content
        $data = json_decode($this->getRequest()->getContent(), true);

        return $this->get('fos_rest.view_handler')->handle($this->saveForm($form, $data));
    }

    /**
     * modify a customer by its id
     * [PUT] /customers/{id}
     *
     * @param int $id
     * @return FOS\RestBundle\View\View
     */
    public function putCustomersAction($id)
    {
        // find customer
        $customer = $this->getCustomerRepository()->find($id);

        // check if exists
        if ($customer) {
            // create form, decode request and save it if valid
            $view = $this->saveForm(
                $this->createForm(new CustomerType(), $customer),
                json_decode($this->getRequest()->getContent(), true)
            );
        } else {
            // customer does not exists send 404
            $view = View::create()->setStatusCode(404);
            $view->setData("Customer does not exist.");
        }
        return $this->get('fos_rest.view_handler')->handle($view);
    }

    /**
     * delete customer by its id
     * [DELETE] /customerd/{id}
     *
     * @param int $id
     * @return FOS\RestBundle\View\View
     */
    public function deleteCustomersAction($id)
    {
        // find customer
        $customer = $this->getCustomerRepository()->find($id);

        // check if exists
        if ($customer) {
            // remove customer
            $em = $this->getDoctrine()->getEntityManager();
            $em->remove($customer);
            $em->flush();

            // send status message
            $view = View::create()->setData("Customer has been removed.");
        } else {
            // customer does not exists send 404
            $view = View::create()->setStatusCode(404);
            $view->setData("Customer does not exists.");
        }
        return $this->get('fos_rest.view_handler')->handle($view);
    }
}

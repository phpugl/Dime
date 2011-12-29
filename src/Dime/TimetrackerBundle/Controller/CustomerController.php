<?php

namespace Dime\TimetrackerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\View\View;

class CustomerController extends Controller
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
     * [GET] /customer
     *
     * @Route("/")
     */
    public function getCustomersAction()
    {
        $customers = $this->getCustomerRepository()->toArray();
        $view = View::create()->setData($customers);

        return $this->get('fos_rest.view_handler')->handle($view);
    }

    /**
     * load customer
     *
     * [GET] /customer/{id}
     */
    public function getCustomerAction($id)
    {
        $customer = $this->getCustomerRepository()->find($id);
        if ($customer) {
            $view = View::create()->setData($customer->toArray());
        } else {
            $view = View::create()->setStatusCode(404);
            $view->setData("Customer does not exist.");
        }
        return $this->get('fos_rest.view_handler')->handle($view);
    }

    /**
     * create customer
     * [POST] /customers
     * 
     * @return void
     */
    public function postCustomersAction()
    {
         // create new activity
        $customer = new Customer();

        // create activity form
        $form = $this->createForm(new CustomerType(), $customer);

        // get request
        $request = $this->getRequest();

        // convert json to assoc array
        $data = json_decode($request->getContent(), true);

        return $this->get('fos_rest.view_handler')->handle($this->saveForm($form, $data));
    }

    /**
     * modify customer
     * [PUT] /customers/{id}
‚     * 
     * @param int $id 
     * @return void
     */
    public function putCustomersAction($id)
    {
        if ($customer = $this->getCustomerRepository()->find($id)) {
            $view = $this->saveForm(
                $this->createForm(new CustomerType(), $customer),
                json_decode($this->getRequest()->getContent(), true)
            );
        } else {
            $view = View::create()->setStatusCode(404);
            $view->setData("Customer does not exist.");
        }
        return $this->get('fos_rest.view_handler')->handle($view);
    }

    /**
     * delete customer
     * [DELETE] /customer/{id}
     *
     * @param int $id
     * @return void
     */
    public function deleteCustomersAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        if ($customer = $this->getCustomerRepository()->find($id)) {
            $em->remove($customer);
            $em->flush();

            $view = View::create()->setData("Customer has been removed.");
        } else {
            $view = View::create()->setStatusCode(404);
            $view->setData("Customer does not exists.");
        }
        return $this->get('fos_rest.view_handler')->handle($view);
    }
}

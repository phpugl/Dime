<?php

namespace Dime\TimetrackerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\View\View;

class CustomerController extends Controller
{
    /**
     * [GET] /customer
     *
     * @Route("/")
     */
    public function getCustomersAction()
    {
        $customers = $this->getDoctrine()
                          ->getRepository('DimeTimetrackerBundle:Customer')
                          ->findAll();

        $view = View::create()->setStatusCode(200);
        
        $data = array();
        foreach ($customers as $customer) {
            $data[$customer->getId()] = $customer->getName();
        }
        return $this->get('fos_rest.view_handler')->handle($view);
    }

    /**
     * load customer
     *
     * [GET] /customer/{id}
     */
    public function getCustomerAction($id)
    {
        $customer = $this->getDoctrine()->getRepository('DimeTimetrackerBundle:Customer')->find($id);
        if ($customer) {
            $view = View::create()->setStatusCode(200);
            $view->setData(array(
                'id'    => $customer->getId(),
                'name'  => $customer->getName(),
                'alias' => $customer->getAlias()
            ));
        } else {
            $view = View::create()->setStatusCode(404);
        }
        return $this->get('fos_rest.view_handler')->handle($view);
    }

    /**
     * create customer
     * [POST] /customer
     * 
     * @return void
     */
    public function postCustomerAction()
    {
        $customer = new Customer();
        $form = $this->getForm($customer);
        $form->bindRequest($this->getRequest());
        $this->persist($form, $customer);
        $view->setData($form->getData());
        return $this->get('fos_rest.view_handler')->handle($view);
    }

    /**
     * modify customer
     * [PUT] /customer/{slug}
     * 
     * @param string $slug 
     * @return void
     */
    public function putCustomerAction($slug)
    {
        $customer = $this->getDoctrine()->getRepository('DimeTimetrackerBundle:Customer')->find($id);
        $form = $this->getForm($customer);
        $this->persist($form, $customer);
        $view->setData($form->getData());
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
        if ($customer = $this->getDoctrine()->getRepository('Dime\TimetrackerBundle\Entity\Customer')->find($id)) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->remove($customer);
        }
    }

    /**
     * persist customer
     * 
     * @param $form
     * @param Dime\TimetrackerBundle\Entity\customer $customer
     */
    protected function persist($form, Dime\TimetrackerBundle\Entity\Customer $customer)
    {
        $form->bindRequest($this->getRequest());
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($customer);
            $em->flush();
        }
    }

    protected function getForm($customer)
    {
        return $this->formFactory->createBuilder('form', $customer)
            ->add('name',  'string', array('required' => true))
            ->add('alias', 'string', array('required' => true))
            ->getForm();
    }
}

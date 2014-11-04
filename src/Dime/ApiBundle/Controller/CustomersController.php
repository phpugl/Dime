<?php

namespace Dime\ApiBundle\Controller;

use Dime\TimetrackerBundle\Entity\Customer;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class CustomersController extends FOSRestController
{
    /**
     * List all customers.
     *
     * @ApiDoc()
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getCustomersAction()
    {
        $list = $this->getRepository()->findAll();

        return $this->handleView($this->view($list));
    }

    /**
     * List customer.
     *
     * @ApiDoc()
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function getCustomerAction($id)
    {
        $model = $this->getRepositor()->find($id);

        if (!$model) {
            throw $this->createNotFoundException();
        }

        return $this->handleView($this->view($model));
    }

    /**
     * Add new customer.
     *
     * @ApiDoc()
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postCustomersAction(Request $request)
    {
        return $this->processForm(new Customer(), $request, true);
    }

    /**
     * Update customer.
     *
     * @ApiDoc()
     * @param int $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function putCustomersAction($id, Request $request)
    {
        $model = $this->getRepositor()->find($id);

        if (!$model) {
            throw $this->createNotFoundException();
        }

        return $this->processForm($model, $request, false);
    }

    /**
     * Delete customer.
     *
     * @ApiDoc()
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function deleteCustomersAction($id)
    {
        $model = $this->getRepository()->find($id);
        if (!$model) {
            throw $this->createNotFoundException();
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($model);
        $em->flush();

        return $this->handleView($this->view(null, 204));
    }

    /**
     * Gets the customer repository
     *
     * @return \Doctrine\Common\Persistence\AbstractManagerRegistry
     */
    private function getRepository()
    {
        return $this->getDoctrine()->getRepository('DimeCoreBundle:Customer');
    }

    /**
     * Validates and saves the customers
     *
     * @param Customer $customer
     * @param Request $request
     * @param bool $new New object?
     * @return \Symfony\Component\HttpFoundation\Response
     */
    private function processForm(Customer $customer, Request $request, $new = false)
    {
        $statusCode = $new ? 201 : 204;
        //FIXME
        $form = $this->createForm(new TodoType(), $customer);

        $data = $request->request->all();
        $children = $form->all();
        $toBind = array_intersect_key($data, $children);
        $form->submit($toBind);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($customer);
            $em->flush();
            return $this->handleView($this->view($new ? $customer : null, $statusCode));
        }
        return $this->handleView($this->view($form, 400));
    }

}
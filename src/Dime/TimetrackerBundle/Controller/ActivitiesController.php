<?php

namespace Dime\TimetrackerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\View\View;

class ActivitiesController extends Controller
{
    /**
     * [GET] /activities
     *
     * @Route("/")
     */
    public function getActivitiesAction()
    {
        $activities = $this->getDoctrine()->getRepository('DimeTimetrackerBundle:Activity')->allToArray();

        $view = View::create()
                  ->setStatusCode(200)
                  ->setData($activities)
                  ;

        return $this->get('fos_rest.view_handler')->handle($view);
    }

    /**
     * load activity
     *
     * [GET] /activity/{id}
     */
    public function getActivityAction($id)
    {
        $activity = $this->getDoctrine()->getRepository('DimeTimetrackerBundle:Activity')->find($id);
        if ($activity) {
            $view = View::create()->setStatusCode(200);
            $view->setData($activity->toArray());
        } else {
            $view = View::create()->setStatusCode(404);
        }
        return $this->get('fos_rest.view_handler')->handle($view);
    }

    /**
     * create activity
     * [POST] /activity
     * 
     * @return void
     */
    public function postActivityAction()
    {
        $activity = new Activity();
        $form = $this->getForm($activity);
        $form->bindRequest($this->getRequest());
        $this->persist($form, $activity);
        $view->setData($form->getData());
        return $this->get('fos_rest.view_handler')->handle($view);
    }

    /**
     * modify activity
     * [PUT] /activity/{id}
     * 
     * @param string $id
     * @return void
     */
    public function putActivityAction($id)
    {
        $activity = $this->getDoctrine()->getRepository('DimeTimetrackerBundle:Activity')->find($id);
        $form = $this->getForm($activity);
        $this->persist($form, $activity);
        $view->setData($form->getData());
        return $this->get('fos_rest.view_handler')->handle($view);
    }

    /**
     * delete activity
     * [DELETE] /activity/{id}
     *
     * @param int $id
     * @return void
     */
    public function deleteActivityAction($id)
    {
        if ($activity = $this->getDoctrine()->getRepository('DimeTimetrackerBundle:Activity')->find($id)) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->remove($activity);
        }
    }

    /**
     * persist activity
     * 
     * @param $form
     * @param Dime\TimetrackerBundle\Entity\Activity $activity
     */
    protected function persist($form, Dime\TimetrackerBundle\Entity\Activity $activity)
    {
        $form->bindRequest($this->getRequest());
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($activity);
            $em->flush();
        }
    }

    /**
     * getForm 
     * 
     * @todo
     *
     * @param mixed $activity 
     * @return void
     */
    protected function getForm($activity)
    {
        return $this->formFactory->createBuilder('form', $activity)
            ->add('name',          'string',   array('required' => true))
            ->add('duration',      'integer',  array('required' => false))
            ->add('startedAt',     'datetime', array('required' => false))
            ->add('stoppedAt',     'datetime', array('required' => false))
            ->add('description',   'string',   array('required' => false))
            ->add('rate',          'integer',  array('required' => false))
            ->add('service',       'Service',  array('required' => false))
            ->add('customer',      'Customer', array('required' => false))
            ->add('project',       'Project',  array('required' => false))
            ->getForm();
    }
}

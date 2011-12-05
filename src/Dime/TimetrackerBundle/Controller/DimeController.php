<?php

namespace Dime\TimetrackerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\View\View;

class DimeController extends Controller
{
    /**
     * save form
     *
     * @return FOS\RestBundle\View\View
     */
    protected function saveForm($form)
    {
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();

            /** @todo: set user */
            $user = $em->getRepository('DimeTimetrackerBundle:User')->findOneByEmail('johndoe@example.com');
            $form->getData()->setUser($user);

            // save change to database
            $em->persist($form->getData());
            $em->flush();

            // push back the new object
            $view = View::create()->setStatusCode(200);
            $view->setData($form->getData()->toArray());
        } else {
            die($form->getErrorsAsString());
            // return error sting from form
            $view = View::create()->setStatusCode(404);
            $view->setData($form->getErrorsAsString());
        }
        
        return $view;
    }
}

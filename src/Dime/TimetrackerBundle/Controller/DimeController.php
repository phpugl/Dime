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
     * @param Form  $form
     * @param array $data
     *
     * @return FOS\RestBundle\View\View
     */
    protected function saveForm($form, $data)
    {
        // clean array from non existing keys to avoid extra data 
        foreach ($data as $key => $value) {
            if (!$form->has($key)) {
                unset($data[$key]);
            }
        }

        // bind data to form
        $form->bind($data);
        
        if ($form->isValid()) {
            
            $em = $this->getDoctrine()->getEntityManager();

            /** @todo: set user */
            $user = $em->getRepository('DimeTimetrackerBundle:User')->findOneByEmail('johndoe@example.com');

            if (is_object($form->getData()) && method_exists($form->getData(), 'setUser')) {
                $form->getData()->setUser($user);
            }

            // save change to database
            $em->persist($form->getData());
            $em->flush();  

            // push back the new object
            $view = View::create()->setStatusCode(200);
            $view->setData($form->getData()->toArray());
        } else {
            // return error string from form
            $view = View::create()->setStatusCode(400);
            $view->setData(array('error'=>$form->getErrorsAsString()));
        }
        
        return $view;
    }
}

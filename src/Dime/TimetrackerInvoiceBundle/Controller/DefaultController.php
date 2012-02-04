<?php

namespace Dime\TimetrackerInvoiceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class DefaultController extends Controller
{
    
    public function indexAction($name)
    {
        return $this->render('DimeTimetrackerInvoiceBundle:Default:index.html.twig', array('name' => $name));
    }
}

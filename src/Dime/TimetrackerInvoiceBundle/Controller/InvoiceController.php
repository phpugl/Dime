<?php

namespace Dime\TimetrackerInvoiceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;


class InvoiceController extends Controller
{
  
  public function indexAction()
  {
    $customers = $this->getDoctrine()->getRepository('DimeTimetrackerBundle:Customer')->findAll();
    if (!$customers) {
      throw $this->createNotFoundException('No customer found');
    }
    return $this->render('DimeTimetrackerInvoiceBundle:Invoice:index.html.twig', array('customers' => $customers));
  }

  
  public function activitiesAction($customer_id, Request $request)
  {
    $activities = $this->getDoctrine()->getRepository('DimeTimetrackerBundle:Activity')->findByCustomer($customer_id);
    if (!$activities) {
      throw $this->createNotFoundException('No activity found');
     }  
    $defaultData=array();
    foreach ($activities as $activity){
      $defaultData['description'.$activity->getId()]=$activity->getDescription();
    }
    $builder=$this->createFormBuilder($defaultData);
    foreach ($activities as $activity) {
      $builder->add('description'.$activity->getId(),'text');
      $builder->add('charge'.$activity->getId(), 'checkbox', array('required' => false));
    }
    $form=$builder->getForm();
    if ($request->getMethod() == 'POST') {
      $form->bindRequest($request);  
      if ($form->isValid()) {
        $items=array();  
        $data=$form->getData();
        $sum=0;
        foreach ($activities as $activity){
          $charge=$data['charge'.$activity->getId()];
          if ($charge){ 
            $price=($activity->getDuration()*$activity->getRate())/3600;
            $item['description']=$data['description'.$activity->getId()];
            $item['duration']=number_format($activity->getDuration()/3600, 2);
            $item['rate']=number_format($activity->getRate(), 2);
            $item['price']=number_format($price, 2);
            $items[]=$item;
            $sum+=$price;
            $sum=number_format($sum, 2);
          }          
        }
        $customer=$this->getDoctrine()->getRepository('DimeTimetrackerBundle:Customer')->find($customer_id);
        if (!$customer) {
          throw $this->createNotFoundException('Customer not found');
        } 
        $invoice_customer=$this->getDoctrine()->getRepository('DimeTimetrackerInvoiceBundle:InvoiceCustomer')->findOneByCoreId($customer_id);
        if (!$invoice_customer) {
          throw $this->createNotFoundException('InvoiceCustomer not found');
        } 
        $address=$invoice_customer->getAddress();
        $address=explode("\n",$address);
        return $this->render('DimeTimetrackerInvoiceBundle:Invoice:invoice.html.twig', 
                array('items' => $items, 'sum' => $sum, 'customer' => $customer, 'address' => $address));
      }
    }
    return $this->render('DimeTimetrackerInvoiceBundle:Invoice:activities.html.twig', array('form' => $form->createView(), 'customer_id' => $customer_id, 'activities'=>$activities));
  } 

  
  public function configAction()
  {
    return $this->render('DimeTimetrackerInvoiceBundle:Invoice:config.html.twig');
  }
  
  
  
}

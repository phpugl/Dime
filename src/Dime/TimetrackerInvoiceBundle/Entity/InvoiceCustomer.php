<?php
namespace Dime\TimetrackerInvoiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity
* @ORM\Table(name="invoice_customer")
*/

class InvoiceCustomer {
  /**
  * @ORM\Id
  * @ORM\Column(type="integer")
  * @ORM\GeneratedValue(strategy="AUTO")
  */ 
  protected $id;
  
  /**
   * @ORM\Column(name="core_id", type="integer")
   */
  protected $coreId;
  
  /**
     * @ORM\Column(type="text")
  */  
  protected $address;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set coreId
     *
     * @param integer $coreId
     * @return InvoiceCustomer
     */
    public function setCoreId($coreId)
    {
        $this->coreId = $coreId;
        return $this;
    }

    /**
     * Get coreId
     *
     * @return integer 
     */
    public function getCoreId()
    {
        return $this->coreId;
    }

    /**
     * Set address
     *
     * @param text $address
     * @return InvoiceCustomer
     */
    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }

    /**
     * Get address
     *
     * @return text 
     */
    public function getAddress()
    {
        return $this->address;
    }
}
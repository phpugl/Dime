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
   * @ORM\Column(type="integer")
   */
  protected $core_id;
  
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
     * Set core_id
     *
     * @param integer $coreId
     * @return InvoiceCustomer
     */
    public function setCoreId($coreId)
    {
        $this->core_id = $coreId;
        return $this;
    }

    /**
     * Get core_id
     *
     * @return integer 
     */
    public function getCoreId()
    {
        return $this->core_id;
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
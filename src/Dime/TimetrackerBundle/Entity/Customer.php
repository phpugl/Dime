<?php
namespace Dime\TimetrackerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Dime\TimetrackerBundle\Entity\Project
 *
 * @ORM\Table(name="customers")
 * @ORM\Entity(repositoryClass="Dime\TimetrackerBundle\Entity\CustomerRepository")
 */
class Customer {

    /**
     * @var integer $id
     * 
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @var integer $user
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="customers")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    protected $user;
    
    /**
     * @var string $name
     *
     * @ORM\Column(type="string", length=255)
     */
    protected $name;
    
    /**
     * @var string $alias
     *
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    protected $alias;
    
    /**
     * get entity as array
     * 
     * @return array
     */
    public function toArray()
    {
        return array(
            'id' => $this->getId(),
            'user' => (string) $this->getUser(),
            'user_id' => $this->getUser()->getId(),
            'name' => $this->getName(),
            'alias' => $this->getAlias()
        );
    }    
    
    /**
     * get customer as string
     *
     * @return string
     */
    public function __toString()
    {
        $customer = $this->getName();
        if (empty($customer))
        {
            $customer = $this->getId();
        }

        return $customer;
    }
              

    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Customer
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set user
     *
     * @param Dime\TimetrackerBundle\Entity\User $user
     * @return Customer
     */
    public function setUser(\Dime\TimetrackerBundle\Entity\User $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Get user
     *
     * @return Dime\TimetrackerBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set alias
     *
     * @param string $alias
     * @return Customer
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;
        return $this;
    }

    /**
     * Get alias
     *
     * @return string 
     */
    public function getAlias()
    {
        return $this->alias;
    }
}
<?php
namespace Dime\TimetrackerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Dime\TimetrackerBundle\Entity\Service
 *
 * @ORM\Table(name="services")
 * @ORM\Entity(repositoryClass="Dime\TimetrackerBundle\Entity\ServiceRepository")
 */
class Service {

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
     * @ORM\ManyToOne(targetEntity="User", inversedBy="Services")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    protected $user;

    /**
     * @var string $description
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $name;

    /**
     * @var string $description
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * @var float $rate
     *
     * @ORM\Column(type="decimal", nullable=true)
     */
    protected $rate;

    /**
     * get service as string
     *
     * @return string
     */
    public function __toString()
    {
        $service = $this->getName();
        if (empty($service))
        {
            $service = $this->getId();
        }

        return $service;
    }
              

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
     * Set description
     *
     * @param text $description
     * @return Service
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Get description
     *
     * @return text
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set user
     *
     * @param Dime\TimetrackerBundle\Entity\User $user
     * @return Service
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
     * Set name
     *
     * @param string $name
     * @return Service
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
     * Set rate
     *
     * @param decimal $rate
     * @return Service
     */
    public function setRate($rate)
    {
        $this->rate = $rate;
        return $this;
    }

    /**
     * Get rate
     *
     * @return decimal 
     */
    public function getRate()
    {
        return $this->rate;
    }
    
    /**
     * Export Service to array<
     * 
     * @todo should be generated automatically
     * @return array
     */
    public function toArray() 
    {
        return array(
            'id'          => $this->id,
            'name'        => $this->name,
            'description' => $this->description,
            'rate'        => $this->rate
        );
    }
}

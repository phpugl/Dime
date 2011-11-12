<?php
namespace Dime\TimetrackerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Dime\TimetrackerBundle\Entity\Activity
 *
 * @ORM\Table(name="activities")
 * @ORM\Entity(repositoryClass="Dime\TimetrackerBundle\Entity\ActivityRepository")
 */
class Activity {

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
     * @ORM\ManyToOne(targetEntity="User", inversedBy="activities")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    protected $user;
    
    /**
     * @var integer $duration
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $duration;
    
    /**
     * @var Date $startedAt
     *
     * @ORM\Column(name="started_at", type="datetime", nullable=true)
     */
    protected $startedAt;

    /**
     * @var Date $stoppedAt
     *
     * @ORM\Column(name="stopped_at", type="datetime", nullable=true)
     */
    protected $stoppedAt;
    
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
     * @var string $rateReference
     *
     * @ORM\Column(name="rate_reference", type="string", length=255, nullable=true)
     */
    protected $rateReference;
        

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
     * Set duration
     *
     * @param integer $duration
     * @return Activity
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
        return $this;
    }

    /**
     * Get duration
     *
     * @return int 
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set startedAt
     *
     * @param datetime $startedAt
     * @return Activity
     */
    public function setStartedAt($startedAt)
    {
        $this->startedAt = $startedAt;
        return $this;
    }

    /**
     * Get startedAt
     *
     * @return datetime 
     */
    public function getStartedAt()
    {
        return $this->startedAt;
    }

    /**
     * Set stoppedAt
     *
     * @param datetime $stoppedAt
     * @return Activity
     */
    public function setStoppedAt($stoppedAt)
    {
        $this->stoppedAt = $stoppedAt;
        return $this;
    }

    /**
     * Get stoppedAt
     *
     * @return datetime 
     */
    public function getStoppedAt()
    {
        return $this->stoppedAt;
    }

    /**
     * Set description
     *
     * @param text $description
     * @return Activity
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
     * Set rate
     *
     * @param decimal $rate
     * @return Activity
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
     * Set rateReference
     *
     * @param string $rateReference
     * @return Activity
     */
    public function setRateReference($rateReference)
    {
        $this->rateReference = $rateReference;
        return $this;
    }

    /**
     * Get rateReference
     *
     * @return string 
     */
    public function getRateReference()
    {
        return $this->rateReference;
    }

    /**
     * Set user
     *
     * @param Dime\TimetrackerBundle\Entity\User $user
     * @return Activity
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
}

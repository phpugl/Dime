<?php
namespace Dime\TimetrackerBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\SerializerBundle\Annotation\SerializedName;

/**
 * Dime\TimetrackerBundle\Entity\Activity
 *
 * @ORM\Table(name="activities")
 * @ORM\Entity(repositoryClass="Dime\TimetrackerBundle\Entity\ActivityRepository")
 * @ORM\HasLifecycleCallbacks()
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
     * @var \Dime\TimetrackerBundle\Entity\User $user
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="activities")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    protected $user;

    /**
     * @var \Dime\TimetrackerBundle\Entity\Service $service
     *
     * @ORM\ManyToOne(targetEntity="Service", inversedBy="activities")
     * @ORM\JoinColumn(name="service_id", referencedColumnName="id", nullable=false)
     */
    protected $service;

    /**
     * @var \Dime\TimetrackerBundle\Entity\Customer $customer
     *
     * @ORM\ManyToOne(targetEntity="Customer", inversedBy="activities")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id", nullable=false)
     */
    protected $customer;

    /**
     * @var \Dime\TimetrackerBundle\Entity\Project $project
     *
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="activities")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id", nullable=false)
     */
    protected $project;

    /**
     * @var integer $duration (in seconds)
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $duration;

    /**
     * @var Date $startedAt
     *
     * @SerializedName("startedAt")
     * @ORM\Column(name="started_at", type="datetime", nullable=true)
     */
    protected $startedAt;

    /**
     * @var Date $stoppedAt
     *
     * @SerializedName("stoppedAt")
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
     * @ORM\Column(type="decimal", scale=2, precision=10, nullable=true)
     */
    protected $rate;

    /**
     * @var string $rateReference (considered as enum: customer|project|service)
     *
     * @SerializedName("rateReference")
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
     * Get duration in seconds
     *
     * @return int
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Autogenerate duration if empty
     * 
     * @ORM\prePersist
     * @ORM\preUpdate
     * @return Activity
     */
    public function updateDurationOnEmpty()
    {
        if (empty($this->duration) && !empty($this->startedAt) && !empty($this->stoppedAt)) {
            $this->duration = abs($this->stoppedAt->getTimestamp() - $this->startedAt->getTimestamp());
        }
        return $this;
    }

    /**
     * Get duration in seconds from start to now
     *
     * @return int
     */
    public function getCurrentDuration()
    {
        if ($this->getDuration()) {
            return $this->getDuration();
        }

        if ($this->getStartedAt() instanceof DateTime) {
            if ($this->getStoppedAt() instanceof DateTime) {
                $end = $this->getStoppedAt();
            } else {
                $end = new DateTime('now');
            }

            $duration = $this->getStartedAt()->diff($end);
            return $duration->format('%a') * 24 * 60 * 60
                + $duration->format('%h') * 60 * 60
                + $duration->format('%i') * 60;
        }
    }

    /**
     * Set startedAt
     *
     * @param DateTime|string $startedAt
     * @return Activity
     */
    public function setStartedAt($startedAt)
    {
        if (!$startedAt instanceof DateTime && !empty($startedAt))
        {
            $startedAt = new DateTime($startedAt);
        }
        $this->startedAt = $startedAt;

        return $this;
    }

    /**
     * Get startedAt
     *
     * @return DateTime
     */
    public function getStartedAt()
    {
        return $this->startedAt;
    }

    /**
     * Set stoppedAt
     *
     * @param DateTime|string $stoppedAt
     * @return Activity
     */
    public function setStoppedAt($stoppedAt)
    {
        if (!$stoppedAt instanceof DateTime && !empty($stoppedAt))
        {
            $stoppedAt = new DateTime($stoppedAt);
        }
        $this->stoppedAt = $stoppedAt;
        return $this;
    }

    /**
     * Get stoppedAt
     *
     * @return DateTime
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
     * @param float $rate
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
     * @return float
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

    /**
     * Set service
     *
     * @param Dime\TimetrackerBundle\Entity\Service $service
     * @return Activity
     */
    public function setService($service)
    {
        $this->service = $service;
        return $this;
    }

    /**
     * Get service
     *
     * @return Dime\TimetrackerBundle\Entity\Service
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * Set customer
     *
     * @param Dime\TimetrackerBundle\Entity\Customer $customer
     * @return Activity
     */
    public function setCustomer($customer)
    {
        $this->customer = $customer;
        return $this;
    }

    /**
     * Get customer
     *
     * @return Dime\TimetrackerBundle\Entity\Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Set project
     *
     * @param Dime\TimetrackerBundle\Entity\Project $project
     * @return Activity
     */
    public function setProject($project)
    {
        $this->project = $project;
        return $this;
    }

    /**
     * Get project
     *
     * @return Dime\TimetrackerBundle\Entity\Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * get project as string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getId();
    }
}

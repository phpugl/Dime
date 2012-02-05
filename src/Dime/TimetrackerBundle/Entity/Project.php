<?php
namespace Dime\TimetrackerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\SerializerBundle\Annotation\SerializedName;

/**
 * Dime\TimetrackerBundle\Entity\Project
 *
 * @ORM\Table(name="projects")
 * @ORM\Entity(repositoryClass="Dime\TimetrackerBundle\Entity\ProjectRepository")
 */
class Project {

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
     * @ORM\ManyToOne(targetEntity="User", inversedBy="projects")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    protected $user;

    /**
     * @var \Dime\TimetrackerBundle\Entity\Customer $customer
     *
     * @ORM\ManyToOne(targetEntity="Customer", inversedBy="projects")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id", nullable=false)
     */
    protected $customer;

    /**
     * @var string $duration
     *
     * @ORM\Column(type="string", length=255)
     */
    protected $name;

    /**
     * @var Date $startedAt
     *
     * @SerializedName("startetAt")
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
     * @var Date $stoppedAt
     *
     * @ORM\Column(name="deadline", type="datetime", nullable=true)
     */
    protected $deadline;

    /**
     * @var string $description
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * @var integer $budgetPrice
     *
     * @SerializedName("budgetPrice")
     * @ORM\Column(name="budget_price", type="integer", nullable=true)
     */
    protected $budgetPrice;

    /**
     * @var integer $fixedPrice
     *
     * @SerializedName("fixedPrice")
     * @ORM\Column(name="fixed_price", type="integer", length=255, nullable=true)
     */
    protected $fixedPrice;

    /**
     * @var integer $budgetTime
     *
     * @SerializedName("budgetTime")
     * @ORM\Column(name="budget_time", type="integer", length=255, nullable=true)
     */
    protected $budgetTime;

    /**
     * @var float $rate
     *
     * @ORM\Column(type="decimal", scale=2, precision=10, nullable=true)
     */
    protected $rate;

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
     * Set name
     *
     * @param string $name
     * @return Project
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
     * Set startedAt
     *
     * @param datetime $startedAt
     * @return Project
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
     * @return Project
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
     * Set deadline
     *
     * @param datetime $deadline
     * @return Project
     */
    public function setDeadline($deadline)
    {
        $this->deadline = $deadline;
        return $this;
    }

    /**
     * Get deadline
     *
     * @return datetime
     */
    public function getDeadline()
    {
        return $this->deadline;
    }

    /**
     * Set description
     *
     * @param text $description
     * @return Project
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
     * Set budgetPrice
     *
     * @param integer $budgetPrice
     * @return Project
     */
    public function setBudgetPrice($budgetPrice)
    {
        $this->budgetPrice = $budgetPrice;
        return $this;
    }

    /**
     * Get budgetPrice
     *
     * @return int
     */
    public function getBudgetPrice()
    {
        return $this->budgetPrice;
    }

    /**
     * Set fixedPrice
     *
     * @param integer $fixedPrice
     * @return Project
     */
    public function setFixedPrice($fixedPrice)
    {
        $this->fixedPrice = $fixedPrice;
        return $this;
    }

    /**
     * Get fixedPrice
     *
     * @return int
     */
    public function getFixedPrice()
    {
        return $this->fixedPrice;
    }

    /**
     * Set budgetTime
     *
     * @param integer $budgetTime
     * @return Project
     */
    public function setBudgetTime($budgetTime)
    {
        $this->budgetTime = $budgetTime;
        return $this;
    }

    /**
     * Get budgetTime
     *
     * @return int
     */
    public function getBudgetTime()
    {
        return $this->budgetTime;
    }

    /**
     * Set rate
     *
     * @param decimal $rate
     * @return Project
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
     * Set user
     *
     * @param Dime\TimetrackerBundle\Entity\User $user
     * @return Project
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
     * Set customer
     *
     * @param integer $customer
     * @return Project
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
     * Export project to array
     *
     * @todo should be generated automatically
     * @return array
     */
    public function toArray()
    {
        return array(
            'id'          => $this->getId(),
            'name'        => $this->getName(),
            'description' => $this->getDescription(),
            'rate'        => $this->getRate(),
            'startedAt'   => $this->getStartedAt(),
            'stoppedAt'   => $this->getStoppedAt(),
            'deadline'    => $this->getDeadline(),
            'budgetPrice' => $this->getBudgetPrice(),
            'fixedPrice'  => $this->getFixedPrice(),
            'budgetTime'  => $this->getBudgetTime(),
            'customer'    => $this->getCustomer()
        );
    }

    /**
     * get project as string
     *
     * @return string
     */
    public function __toString()
    {
        return (empty($this->name)) ? $this->getId() : $this->getName();
    }
}

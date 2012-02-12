<?php

namespace Dime\TimetrackerBundle\Entity;

use \DateTime;
use Doctrine\ORM\Mapping as ORM;
use JMS\SerializerBundle\Annotation\SerializedName;

/**
 * Dime\TimetrackerBundle\Entity\Timeslice
 *
 * @ORM\Table(name="timeslices")
 * @ORM\Entity(repositoryClass="Dime\TimetrackerBundle\Entity\TimesliceRepository")
 */
class Timeslice
{
    /**
     * @var integer $id
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \Dime\TimetrackerBundle\Entity\Activity $activity
     *
     * @ORM\ManyToOne(targetEntity="Activity", inversedBy="timeslices")
     * @ORM\JoinColumn(name="activity_id", referencedColumnName="id", nullable=false)
     */
    protected $activity;

    /**
     * @var integer $duration (in seconds)
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $duration;

    /**
     * @var datetime $started_at
     *
     * @SerializedName("startedAt")
     * @ORM\Column(name="started_at", type="datetime", nullable=true)
     */
    private $startedAt;

    /**
     * @var datetime $stopped_at
     *
     * @SerializedName("stoppedAt")
     * @ORM\Column(name="stopped_at", type="datetime", nullable=true)
     */
    private $stoppedAt;

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
     * Set activity
     *
     * @param Dime\TimetrackerBundle\Entity\Activity $activity
     * @return Activity
     */
    public function setActivity($activity)
    {
        $this->activity = $activity;
        return $this;
    }

    /**
     * Get activity
     *
     * @return Dime\TimetrackerBundle\Entity\Activity
     */
    public function getActivity()
    {
        return $this->activity;
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
     * Set started_at
     *
     * @param datetime $startedAt
     * @return Timeslice
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
     * Get started_at
     *
     * @return datetime
     */
    public function getStartedAt()
    {
        return $this->startedAt;
    }

    /**
     * Set stopped_at
     *
     * @param datetime $stoppedAt
     * @return Timeslice
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
     * Get stopped_at
     *
     * @return datetime
     */
    public function getStoppedAt()
    {
        return $this->stoppedAt;
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
}
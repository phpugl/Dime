<?php

namespace Dime\TimetrackerBundle\Entity;

use DateTime;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;

/**
 * Dime\TimetrackerBundle\Entity\Timeslice
 *
 * @ORM\Table(name="timeslices")
 * @ORM\Entity(repositoryClass="Dime\TimetrackerBundle\Entity\TimesliceRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Timeslice extends Entity
{
    /**
     * @var Activity $activity
     *
     * @Assert\NotNull()
     * @ORM\ManyToOne(targetEntity="Activity", inversedBy="timeslices", cascade="persist")
     * @ORM\JoinColumn(name="activity_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    protected $activity;

    /**
     * @var ArrayCollection $tags
     *
     * @JMS\Type("array")
     * @JMS\SerializedName("tags")
     * @ORM\ManyToMany(targetEntity="Tag", cascade="persist")
     * @ORM\JoinTable(name="timeslice_tags")
     */
    protected $tags;

    /**
     * @var integer $duration (in seconds)
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $duration = 0;

    /**
     * @var datetime $startedAt
     *
     * @Assert\DateTime()
     * @JMS\SerializedName("startedAt")
     * @ORM\Column(name="started_at", type="datetime", nullable=true)
     */
    protected $startedAt;

    /**
     * @var datetime $stoppedAt
     *
     * @Assert\DateTime()
     * @JMS\SerializedName("stoppedAt")
     * @ORM\Column(name="stopped_at", type="datetime", nullable=true)
     */
    protected $stoppedAt;


    /**
     * Entity constructor
     */
    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

    /**
     * Set activity
     *
     * @param  Activity  $activity
     * @return Timeslice
     */
    public function setActivity(Activity $activity)
    {
        $this->activity = $activity;

        return $this;
    }

    /**
     * Get activity
     *
     * @return Activity
     */
    public function getActivity()
    {
        return $this->activity;
    }

    /**
     * Set duration
     *
     * @param  integer   $duration
     * @return Timeslice
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
     * @param  DateTime  $startedAt
     * @return Timeslice
     */
    public function setStartedAt($startedAt)
    {
        if (!$startedAt instanceof DateTime && !empty($startedAt)) {
            $startedAt = new DateTime($startedAt);
        }
        $this->startedAt = $startedAt;

        return $this;
    }

    /**
     * Get started_at
     *
     * @return DateTime
     */
    public function getStartedAt()
    {
        return $this->startedAt;
    }

    /**
     * Set stopped_at
     *
     * @param  DateTime  $stoppedAt
     * @return Timeslice
     */
    public function setStoppedAt($stoppedAt)
    {
        if (!$stoppedAt instanceof DateTime && !empty($stoppedAt)) {
            $stoppedAt = new DateTime($stoppedAt);
        }
        $this->stoppedAt = $stoppedAt;

        return $this;
    }

    /**
     * Get stopped_at
     *
     * @return DateTime
     */
    public function getStoppedAt()
    {
        return $this->stoppedAt;
    }

    /**
     * Add tag
     *
     * @param  Tag      $tag
     * @return Timeslice
     */
    public function addTag(Tag $tag)
    {
        $this->tags[] = $tag;

        return $this;
    }

    /**
     * Remove tags
     *
     * @param Tag $tags
     * @return Timeslice
     */
    public function removeTag(Tag $tags)
    {
        $this->tags->removeElement($tags);

        return $this;
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Set tags
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $tags
     * @return Timeslice
     */
    public function setTags(ArrayCollection $tags)
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * Auto generate duration if empty
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     * @return Timeslice
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

<?php

namespace Dime\TimetrackerBundle\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Behave;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Timeslice - a duration or period of time in a activity.
 *
 * @ORM\Table(name="timeslices")
 * @ORM\UserEntity(repositoryClass="Dime\CoreBundle\Entity\TimesliceRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Timeslice implements TimesliceInterface
{
    /**
     * @var integer $id
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var integer $duration (in seconds)
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $duration = 0;

    /**
     * @var Datetime $startedAt
     *
     * @Assert\DateTime()
     * @JMS\SerializedName("startedAt")
     * @ORM\Column(name="started_at", type="datetime", nullable=true)
     */
    protected $startedAt;

    /**
     * @var Datetime $stoppedAt
     *
     * @Assert\DateTime()
     * @JMS\SerializedName("stoppedAt")
     * @ORM\Column(name="stopped_at", type="datetime", nullable=true)
     */
    protected $stoppedAt;

    /**
     * @var Activity $activity
     *
     * @Assert\NotNull()
     * @ORM\ManyToOne(targetEntity="Activity", inversedBy="timeslices", cascade="persist")
     * @ORM\JoinColumn(name="activity_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    protected $activity;

    /**
     * @var User $user
     *
     * @JMS\Exclude
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    protected $user;

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
     * @var datetime $createdAt
     *
     * @JMS\SerializedName("createdAt")
     * @Behave\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    /**
     * @var datetime $updatedAt
     *
     * @JMS\SerializedName("updatedAt")
     * @Behave\Timestampable(on="update")
     * @ORM\Column(name="updated_at", type="datetime")
     */
    protected $updatedAt;


    /**
     * Timeslice constructor
     */
    public function __construct()
    {
        $this->tags = new ArrayCollection();
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
     * Get duration in seconds
     *
     * @return int
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set duration
     *
     * @param  integer $duration
     * @return $this
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Set started_at
     *
     * @param  DateTime $startedAt
     * @return $this
     */
    public function setStartedAt(DateTime $startedAt)
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
     * @param  DateTime $stoppedAt
     * @return $this
     */
    public function setStoppedAt(DateTime $stoppedAt)
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
     * Get activity
     *
     * @return ActivityInterface
     */
    public function getActivity()
    {
        return $this->activity;
    }

    /**
     * Set activity
     *
     * @param  ActivityInterface $activity
     * @return $this
     */
    public function setActivity(ActivityInterface $activity)
    {
        $this->activity = $activity;

        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set user
     *
     * @param  UserInterface $user
     * @return $this
     */
    public function setUser(UserInterface $user)
    {
        $this->user = $user;

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
     * @param ArrayCollection $tags
     * @return $this
     */
    public function setTags(ArrayCollection $tags)
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * Add tag
     *
     * @param  TagInterface $tag
     * @return $this
     */
    public function addTag(TagInterface $tag)
    {
        $this->tags[] = $tag;

        return $this;
    }

    /**
     * Remove tag
     *
     * @param TagInterface $tag
     * @return $this
     */
    public function removeTag(TagInterface $tag)
    {
        $this->tags->removeElement($tag);

        return $this;
    }

    /**
     * Get created at datetime
     *
     * @return Datetime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Get updated at datetime
     *
     * @return Datetime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Auto generate duration if empty
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     * @return $this
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
        $result = $this->getDuration();
        if (!$result) {
            if ($this->getStartedAt() instanceof DateTime) {
                if ($this->getStoppedAt() instanceof DateTime) {
                    $end = $this->getStoppedAt();
                } else {
                    $end = new DateTime('now');
                }

                $duration = $this->getStartedAt()->diff($end);

                return ($duration->format('%a') * 24 * 60 * 60)
                + ($duration->format('%h') * 60 * 60)
                + ($duration->format('%i') * 60);
            }
        }
        return $result;
    }
}

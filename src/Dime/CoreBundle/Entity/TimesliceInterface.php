<?php
namespace Dime\TimetrackerBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Interface TimesliceInterface
 * @package Dime\TimetrackerBundle\Entity
 */
interface TimesliceInterface
{

    public function getId();

    public function getDuration();

    public function setDuration($duration);

    public function getStartedAt();

    public function setStartedAt(\DateTime $startedAt);

    public function getStoppedAt();

    public function setStoppedAt(\DateTime $stoppedAt);

    public function getActivity();

    public function setActivity(ActivityInterface $activity);

    public function getUser();

    public function setUser(UserInterface $user);

    public function getTags();

    public function setTags(ArrayCollectionlection $tags);

    public function addTag(TagInterface $tag);

    public function removeTag(TagInterface $tag);

} 
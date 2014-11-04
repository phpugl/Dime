<?php
namespace Dime\TimetrackerBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Interface ActivityInterface
 * @package Dime\TimetrackerBundle\Entity
 */
interface ActivityInterface
{

    public function getId();

    public function getDescription();

    public function setDescription($description);

    public function getRate();

    public function setRate($rate);

    public function getRateReference();

    public function setRateReference($rateReference);

    public function getUser();

    public function setUser(UserInterface $user);

    public function getCustomer();

    public function setCustomer(CustomerInterface $customer);

    public function getProject();

    public function setProject(ProjectInterface $project);

    public function getService();

    public function setService(ServiceInterface $service);

    public function getTimeslices();

    public function setTimeslices(ArrayCollection $timeslices);

    public function addTimeslice(TimesliceInterface $timeslice);

    public function removeTimeslice(TimesliceInterface $timeslice);

    public function getTags();

    public function setTags(ArrayCollection $tags);

    public function addTag(TagInterface $tag);

    public function removeTag(TagInterface $tag);

} 
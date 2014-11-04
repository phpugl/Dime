<?php
namespace Dime\TimetrackerBundle\Entity;

/**
 * Interface TagInterface
 * @package Dime\TimetrackerBundle\Entity
 */
interface TagInterface
{

    public function getId();

    public function getName();

    public function setName($name);

    public function isEnabled();

    public function setEnabled($enable);

    public function getUser();

    public function setUser(UserInterface $user);

}
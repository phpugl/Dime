<?php
namespace Dime\TimetrackerBundle\Entity;

/**
 * Interface UserInterface
 * @package Dime\TimetrackerBundle\Entity
 */
interface UserInterface
{

    public function getId();

    public function getFirstname();

    public function setFirstname($firstname);

    public function getLastname();

    public function setLastname($lastname);

    public function getEmail();

    public function setEmail($email);

} 
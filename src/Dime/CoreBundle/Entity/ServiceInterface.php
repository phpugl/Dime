<?php
namespace Dime\TimetrackerBundle\Entity;

/**
 * Interface ServiceInterface
 * @package Dime\TimetrackerBundle\Entity
 */
interface ServiceInterface
{

    public function getId();

    public function getName();

    public function setName($name);

    public function getAlias();

    public function setAlias($alias);

    public function getDescription();

    public function setDescription($description);

    public function getRate();

    public function setRate($rate);

    public function isEnabled();

    public function setEnabled($enable);

    public function getUser();

    public function setUser(UserInterface $user);

} 
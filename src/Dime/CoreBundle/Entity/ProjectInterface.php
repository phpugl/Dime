<?php
namespace Dime\TimetrackerBundle\Entity;

/**
 * Interface ProjectInterface
 * @package Dime\TimetrackerBundle\Entity
 */
interface ProjectInterface
{

    public function getId();

    public function getName();

    public function setName($name);

    public function getAlias();

    public function setAlias($alias);

    public function getDescription();

    public function setDescription($description);

    public function getBudgetPrice();

    public function setBudgetPrice($budgetPrice);

    public function getBudgetTime();

    public function setBudgetTime($budgetTime);

    public function isBudgetFixed();

    public function setBudgetFixed($budgetFixed);

    public function getRate();

    public function setRate($rate);

    public function isEnabled();

    public function setEnabled($enable);

    public function getUser();

    public function setUser(UserInterface $user);

    public function getCustomer();

    public function setCustomer(CustomerInterface $customer);

} 
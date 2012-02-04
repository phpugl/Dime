<?php
namespace Dime\TimetrackerBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Dime\TimetrackerBundle\Entity\Customer;

class LoadCustomers extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param Doctrine\Common\Persistence\ObjectManager $manager
     */
    function load(ObjectManager $manager)
    {
        $customer = new Customer();
        $customer->setName('CWE Customer');
        $customer->setAlias('CC');
        $customer->setUser($manager->merge($this->getReference('default-user')));

        $manager->persist($customer);
        $manager->flush();

        $this->addReference('default-customer', $customer);
    }

    /**
     * the order in which fixtures will be loaded
     *
     * @return int
     */
    public function getOrder()
    {
        return 30;
    }
}


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
    public function load(ObjectManager $manager)
    {
        $customer1 = new Customer();
        $customer1->setName('CWE Customer');
        $customer1->setAlias('cc');
        $customer1->setUser($manager->merge($this->getReference('default-user')));

        $manager->persist($customer1);

        $customer2 = new Customer();
        $customer2->setName('Another Customer');
        $customer2->setAlias('ac');
        $customer2->setUser($manager->merge($this->getReference('default-user')));

        $manager->persist($customer2);
        $manager->flush();

        $this->addReference('default-customer', $customer1);
        $this->addReference('another-customer', $customer2);
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


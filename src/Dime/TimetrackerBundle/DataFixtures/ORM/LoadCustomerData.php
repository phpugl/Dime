<?php
namespace Dime\TimetrackerBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Dime\TimetrackerBundle\Entity\Customer;

class LoadCustomerData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load($manager)
    {
        $customer = new Customer();
        $customer->setName('CWE Customer');
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
        return 3;
    }
}


<?php
namespace Dime\TimetrackerBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Dime\TimetrackerBundle\Entity\Project;

class LoadProjectData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load($manager)
    {
        $phpugl = new Project();
        $phpugl->setName('CWE2001');
        $phpugl->setDescription('PHPUGL Codingweekend');
        $phpugl->setUser($manager->merge($this->getReference('default-user')));
        $phpugl->setCustomer($manager->merge($this->getReference('default-customer')));
        $manager->persist($phpugl);

        $manager->flush();
    }
    
    /**
     * the order in which fixtures will be loaded
     * 
     * @return int
     */
    public function getOrder()
    {
        return 4;
    }
}


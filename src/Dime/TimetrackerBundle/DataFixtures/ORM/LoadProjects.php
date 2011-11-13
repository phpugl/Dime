<?php
namespace Dime\TimetrackerBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Dime\TimetrackerBundle\Entity\Project;

class LoadProjects extends AbstractFixture implements OrderedFixtureInterface
{
    public function load($manager)
    {
        $phpugl = new Project();
        $phpugl->setName('CWE2011');
        $phpugl->setDescription('PHPUGL Coding Weekend 2011');
        $phpugl->setUser($manager->merge($this->getReference('default-user')));
        $phpugl->setCustomer($manager->merge($this->getReference('default-customer')));

        $manager->persist($phpugl);
        $manager->flush();

        $this->addReference('default-project', $phpugl);
    }

    /**
     * the order in which fixtures will be loaded
     *
     * @return int
     */
    public function getOrder()
    {
        return 40;
    }
}


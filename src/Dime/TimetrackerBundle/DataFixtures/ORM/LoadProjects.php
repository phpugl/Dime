<?php
namespace Dime\TimetrackerBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Dime\TimetrackerBundle\Entity\Project;

class LoadProjects extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $phpugl = new Project();
        $phpugl->setName('CWE2011');
        $phpugl->setAlias('cwe2011');
        $phpugl->setDescription('PHPUGL Coding Weekend 2011');
        $phpugl->setUser($manager->merge($this->getReference('default-user')));
        $phpugl->setCustomer($manager->merge($this->getReference('default-customer')));

        $manager->persist($phpugl);
        $this->addReference('default-project', $phpugl);

        $phpugl = new Project();
        $phpugl->setName('CWE2012');
        $phpugl->setAlias('cwe2012');
        $phpugl->setDescription('PHPUGL Coding Weekend 2012');
        $phpugl->setUser($manager->merge($this->getReference('default-user')));
        $phpugl->setCustomer($manager->merge($this->getReference('another-customer')));

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
        return 40;
    }
}


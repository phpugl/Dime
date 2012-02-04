<?php
namespace Dime\TimetrackerBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Dime\TimetrackerBundle\Entity\Service;

class LoadServices extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param Doctrine\Common\Persistence\ObjectManager $manager
     */
    function load(ObjectManager $manager)
    {
        $consulting = new Service();
        $consulting->setName('consulting');
        $consulting->setRate(80);
        $consulting->setUser($manager->merge($this->getReference('default-user')));
        $manager->persist($consulting);
        $this->addReference('consulting', $consulting);

        $requirements = new Service();
        $requirements->setName('requirements');
        $requirements->setRate(50);
        $requirements ->setUser($manager->merge($this->getReference('default-user')));
        $manager->persist($requirements);
        $this->addReference('requirements', $requirements);

        $development = new Service();
        $development->setName('development');
        $development->setRate(50);
        $development ->setUser($manager->merge($this->getReference('default-user')));
        $manager->persist($development);
        $this->addReference('development', $development);

        $testing = new Service();
        $testing->setName('testing');
        $testing->setRate(30);
        $testing ->setUser($manager->merge($this->getReference('default-user')));
        $manager->persist($testing);
        $this->addReference('testing', $testing);

        $documentation = new Service();
        $documentation->setName('documentation');
        $documentation->setRate(70);
        $documentation ->setUser($manager->merge($this->getReference('default-user')));
        $manager->persist($documentation);
        $this->addReference('documentation', $documentation);

        $projectManagement = new Service();
        $projectManagement->setName('project management');
        $projectManagement->setRate(60);
        $projectManagement ->setUser($manager->merge($this->getReference('default-user')));
        $manager->persist($projectManagement);
        $this->addReference('projectManagement', $projectManagement);

        $qualityAssurance = new Service();
        $qualityAssurance->setName('quality assurance');
        $qualityAssurance->setRate(50);
        $qualityAssurance ->setUser($manager->merge($this->getReference('default-user')));
        $manager->persist($qualityAssurance);
        $this->addReference('qualityAssurance', $qualityAssurance);

        $systemAnalysis = new Service();
        $systemAnalysis->setName('system analysis');
        $systemAnalysis->setRate(80);
        $systemAnalysis ->setUser($manager->merge($this->getReference('default-user')));
        $manager->persist($systemAnalysis);
        $this->addReference('systemAnalysis', $systemAnalysis);

        $support = new Service();
        $support->setName('support');
        $support->setRate(60);
        $support ->setUser($manager->merge($this->getReference('default-user')));
        $manager->persist($support);
        $this->addReference('support', $support);

        $infrastructure = new Service();
        $infrastructure->setName('infrastructure');
        $infrastructure->setRate(50);
        $infrastructure ->setUser($manager->merge($this->getReference('default-user')));
        $manager->persist($infrastructure);
        $this->addReference('infrastructure', $infrastructure);

        $manager->flush();
    }

    /**
     * the order in which fixtures will be loaded
     *
     * @return int
     */
    public function getOrder()
    {
        return 20;
    }
}


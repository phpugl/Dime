<?php
namespace Dime\TimetrackerBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Dime\TimetrackerBundle\Entity\Service;

class LoadServiceData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load($manager)
    {
        $consulting = new Service();
        $consulting->setName('consulting');
        $consulting->setRate('80');
        $consulting->setUser($manager->merge($this->getReference('default-user')));
        $manager->persist($consulting);

        $requirements = new Service();
        $requirements->setName('requirements');
        $requirements->setRate('50');
        $requirements ->setUser($manager->merge($this->getReference('default-user')));
        $manager->persist($requirements);

        $development = new Service();
        $development->setName('development');
        $development->setRate('50');
        $development ->setUser($manager->merge($this->getReference('default-user')));
        $manager->persist($development);

        $testing = new Service();
        $testing->setName('testing');
        $testing->setRate('30');
        $testing ->setUser($manager->merge($this->getReference('default-user')));
        $manager->persist($testing);

        $documentation = new Service();
        $documentation->setName('documentation');
        $documentation->setRate('30');
        $documentation ->setUser($manager->merge($this->getReference('default-user')));
        $manager->persist($documentation);

        $projectManagement = new Service();
        $projectManagement->setName('project management');
        $projectManagement->setRate('60');
        $projectManagement ->setUser($manager->merge($this->getReference('default-user')));
        $manager->persist($projectManagement);

        $qualityAssurance = new Service();
        $qualityAssurance->setName('quality assurance');
        $qualityAssurance->setRate('50');
        $qualityAssurance ->setUser($manager->merge($this->getReference('default-user')));
        $manager->persist($qualityAssurance);

        $systemAnalysis = new Service();
        $systemAnalysis->setName('system analysis');
        $systemAnalysis->setRate('80');
        $systemAnalysis ->setUser($manager->merge($this->getReference('default-user')));
        $manager->persist($systemAnalysis);

        $support = new Service();
        $support->setName('support');
        $support->setRate('60');
        $support ->setUser($manager->merge($this->getReference('default-user')));
        $manager->persist($support);

        $manager->flush();
    }
    
    /**
     * the order in which fixtures will be loaded
     * 
     * @return int
     */
    public function getOrder()
    {
        return 2;
    }
}


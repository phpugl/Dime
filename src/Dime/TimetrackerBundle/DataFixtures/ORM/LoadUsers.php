<?php
namespace Dime\TimetrackerBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Dime\TimetrackerBundle\Entity\User;

class LoadUsers extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $defaultUser = new User();
        $defaultUser->setUsername('admin');
        $defaultUser->setPlainPassword('kitten');
        $defaultUser->setEnabled(true);
        $defaultUser->addRole(User::ROLE_SUPER_ADMIN);

        $defaultUser->setFirstname('Default');
        $defaultUser->setLastname('User');
        $defaultUser->setEmail('admin@example.com');

        $manager->persist($defaultUser);

        $testUser = new User();
        $testUser->setUsername('test');
        $testUser->setPlainPassword('kitten');
        $testUser->setEnabled(true);
        $testUser->addRole(User::ROLE_DEFAULT);

        $testUser->setFirstname('Test');
        $testUser->setLastname('User');
        $testUser->setEmail('test@example.com');

        $manager->persist($testUser);
        
        $manager->flush();

        $this->addReference('default-user', $defaultUser);
        $this->addReference('test-user', $testUser);
    }

    /**
     * the order in which fixtures will be loaded
     *
     * @return int
     */
    public function getOrder()
    {
        return 10;
    }
}

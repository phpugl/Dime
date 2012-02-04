<?php
namespace Dime\TimetrackerBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Dime\TimetrackerBundle\Entity\Activity;

class LoadActivities extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * data for multiple activities to be loaded (adding up to data of base activity)
     *
     * @var array
     */
    protected $data = array(
        'requirements-initial' => array(
            'service'       => 'requirements',
            'duration'      => 7200, // 60 * 120
            'startedAt'     => '2011-11-13 10:02:34',
            'stoppedAt'     => null,
            'description'   => 'cwe: initial requirements meeting with customer',
            'rate'          => 50.0,
            'rateReference' => 'service',
        ),
        'requirements-documentation' => array(
            'service'       => 'requirements',
            'duration'      => 5400, // 60 * 90
            'startedAt'     => '2011-11-13 13:19:01',
            'stoppedAt'     => null,
            'description'   => 'cwe: requirements documentation',
            'rate'          => 50.0,
            'rateReference' => 'service',
        ),
        'environment-setup' => array(
            'service'       => 'infrastructure',
            'duration'      => 2520, // 60 * 42
            'startedAt'     => null,
            'stoppedAt'     => null,
            'description'   => 'cwe: vhost setup, PHP configuration, .vimrc, tags',
            'rate'          => 50.0,
            'rateReference' => 'service',
        ),
        'project-setup' => array(
            'service'       => 'development',
            'duration'      => 4980, // 60 * 83
            'startedAt'     => '2011-11-14 08:24:09',
            'stoppedAt'     => null,
            'description'   => 'cwe: initial project setup (Symfony2, bundles etc.)',
            'rate'          => 50.0,
            'rateReference' => 'service',
        ),
    );

    /**
     * loads fixtures to database
     *
     * @param Doctrine\Common\Persistence\ObjectManager $manager
     * @return LoadActivities
     */
    function load(ObjectManager $manager)
    {
        $baseActivity = new Activity();
        $baseActivity->setUser($manager->merge($this->getReference('default-user')))
                     ->setCustomer($manager->merge($this->getReference('default-customer')))
                     ->setProject($manager->merge($this->getReference('default-project')))
                     ;

        foreach ($this->data as $key => $data)
        {
            $activity = clone $baseActivity;
            $activity->setService($manager->merge($this->getReference($data['service'])))
                     ->setDuration($data['duration'])
                     ->setStartedAt($data['startedAt'])
                     ->setStoppedAt($data['stoppedAt'])
                     ->setDescription($data['description'])
                     ->setRate($data['rate'])
                     ->setRateReference($data['rateReference'])
                     ;

            $manager->persist($activity);
            $this->addReference($key, $activity);
        }

        $manager->flush();

    }

    /**
     * the order in which fixtures will be loaded (compared to other fixture classes)
     *
     * @return int
     */
    public function getOrder()
    {
        return 60;
    }
}

<?php
namespace Dime\TimetrackerBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Dime\TimetrackerBundle\Entity\Timeslice;

class LoadTimeslices extends AbstractFixture implements OrderedFixtureInterface
{

    protected $data = array(
        'requirements-initial' => array(
            'duration'      => 7200, // 60 * 120
            'startedAt'     => '2011-11-13 10:02:34',
            'stoppedAt'     => '2011-11-13 12:02:34',
        ),
        'requirements-documentation' => array(
            'duration'      => 5400, // 60 * 90
            'startedAt'     => '2011-11-13 13:19:01',
            'stoppedAt'     => '2011-11-13 14:49:01',
        ),
        'environment-setup' => array(
            'duration'      => 2520, // 60 * 42
            'startedAt'     => null,
            'stoppedAt'     => null,
        ),
        'project-setup' => array(
            'duration'      => 4980, // 60 * 83
            'startedAt'     => '2011-11-14 08:24:09',
            'stoppedAt'     => '2011-11-14 09:47:09',
        ),
    );

    /**
     * loads fixtures to database
     *
     * @param  Doctrine\Common\Persistence\ObjectManager $manager
     * @return LoadActivities
     */
    public function load(ObjectManager $manager)
    {
        $baseSlice = new Timeslice();
        $baseSlice->setUser($manager->merge($this->getReference('default-user')));

        foreach ($this->data as $key => $data) {
            $slice = clone $baseSlice;

            if ($data['startedAt'] == null && $data['stoppedAt'] == null) {
                $date = new \DateTime();
                $date->setTime(0,0,0);
                $data['startedAt'] = $date;
                $data['stoppedAt'] = $date;
            }

            $slice->setActivity($manager->merge($this->getReference($key)))
                  ->setDuration($data['duration'])
                  ->setStartedAt($data['startedAt'])
                  ->setStoppedAt($data['stoppedAt'])
            ;

            $manager->persist($slice);
            $this->addReference('timeslice-' . $key, $slice);
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
        return 70;
    }
}

<?php

namespace Dime\TimetrackerBundle\Tests\Entity;

use Dime\TimetrackerBundle\Entity\Timeslice;

class TimesliceTest extends \PHPUnit_Framework_TestCase
{
    public function testGetCurrentDurationWithGivenDuration()
    {
        $Timeslice = new Timeslice();
        $Timeslice->setDuration(200);
        $this->assertEquals(200, $Timeslice->getCurrentDuration());
    }

    public function testGetCurrentDurationWithGivenStart()
    {
        $start = new \DateTime('now');
        $start->add(new \DateInterval('PT10H'));

        $Timeslice = new Timeslice();
        $Timeslice->setStartedAt($start);
        $this->assertEquals(36000, $Timeslice->getCurrentDuration());
    }

    public function testGetCurrentDurationWithGivenEnd()
    {
        $start = new \DateTime('now');

        $end = new \DateTime('now');
        $end->add(new \DateInterval('PT10H'));

        $Timeslice = new Timeslice();
        $Timeslice->setStartedAt($start);
        $Timeslice->setStoppedAt($end);
        $this->assertEquals(36000, $Timeslice->getCurrentDuration());
    }
}

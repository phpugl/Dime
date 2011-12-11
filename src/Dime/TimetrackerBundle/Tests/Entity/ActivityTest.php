<?php

namespace Dime\TimetrackerBundle\Tests\Entity;

use Dime\TimetrackerBundle\Entity\Activity;

class ActivityTest extends \PHPUnit_Framework_TestCase
{
    public function testGetCurrentDurationWithGivenDuration() {
        $activity = new Activity();
        $activity->setDuration(200);
        $this->assertEquals(200, $activity->getCurrentDuration());
    }

    public function testGetCurrentDurationWithGivenStart() {
        $start = new \DateTime('now');
        $start->sub(new \DateInterval('PT10H'));

        $activity = new Activity();
        $activity->setStartedAt($start);
        $this->assertEquals(36000, $activity->getCurrentDuration());
    }

    public function testGetCurrentDurationWithGivenEnd() {
        $start = new \DateTime('now');
        $start->sub(new \DateInterval('PT10H'));

        $end = new \DateTime('now');

        $activity = new Activity();
        $activity->setStartedAt($start);
        $activity->setStoppedAt($end);
        $this->assertEquals(36000, $activity->getCurrentDuration());
    }
}

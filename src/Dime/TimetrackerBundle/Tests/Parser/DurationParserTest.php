<?php

namespace Dime\TimetrackerBundle\Tests\Parser;

use Dime\TimetrackerBundle\Parser\DurationParser;

class DurationTest extends \PHPUnit_Framework_TestCase
{
    public function testRun()
    {
        $parser = new DurationParser();

        // 02:30:00
        $parser->setResult(array());
        $result = $parser->run('02:30:00');
        $this->assertArrayHasKey('duration', $result);
        $this->assertArrayHasKey('sign', $result['duration']);
        $this->assertArrayHasKey('number', $result['duration']);
        $this->assertEquals('', $result['duration']['sign']);
        $this->assertEquals(9000, $result['duration']['number']);

        // -02:30:00
        $parser->setResult(array());
        $result = $parser->run('-02:30:00');
        $this->assertEquals('-', $result['duration']['sign']);
        $this->assertEquals(9000, $result['duration']['number']);

        // 02:30h
        $parser->setResult(array());
        $result = $parser->run('02:30h');
        $this->assertEquals('', $result['duration']['sign']);
        $this->assertEquals(9000, $result['duration']['number']);

        // 02:30m
        $parser->setResult(array());
        $result = $parser->run('02:30m');
        $this->assertEquals('', $result['duration']['sign']);
        $this->assertEquals(150, $result['duration']['number']);

        // 2h 30m
        $parser->setResult(array());
        $result = $parser->run('2h 30m');
        $this->assertEquals('', $result['duration']['sign']);
        $this->assertEquals(9000, $result['duration']['number']);

        // 2.5h
        $parser->setResult(array());
        $result = $parser->run('2.5h');
        $this->assertEquals('', $result['duration']['sign']);
        $this->assertEquals(9000, $result['duration']['number']);

        // 2,5h
        $parser->setResult(array());
        $result = $parser->run('2,5h');
        $this->assertEquals('', $result['duration']['sign']);
        $this->assertEquals(9000, $result['duration']['number']);

        // substract 30 minutes
        $result = $parser->run('-30m');
        $this->assertEquals('-', $result['duration']['sign']);
        $this->assertEquals(7200, $result['duration']['number']);

        // Empty
        $parser->setResult(array());
        $result = $parser->run('');
        $this->assertEmpty($result);
    }

    public function testClean()
    {
        $parser = new DurationParser();
        $input = '02:30:00';

        $parser->run($input);

        $output = $parser->clean($input);

        $this->assertEquals('', $output);
    }
}

<?php

namespace Dime\TimetrackerBundle\Tests\Parser;

use Dime\TimetrackerBundle\Parser\ActivityRelationParser;

class ActivityRelationParserTest extends \PHPUnit_Framework_TestCase
{
    public function testRun()
    {
        $parser = new ActivityRelationParser();

        // Customer
        $parser->setResult(array());
        $result = $parser->run('@customer do something');
        $this->assertArrayHasKey('customer', $result);
        $this->assertEquals('customer', $result['customer']);

        $parser->setResult(array());
        $result = $parser->run('do @customer something');
        $this->assertArrayHasKey('customer', $result);
        $this->assertEquals('customer', $result['customer']);

        $parser->setResult(array());
        $result = $parser->run('do something @customer');
        $this->assertArrayHasKey('customer', $result);
        $this->assertEquals('customer', $result['customer']);

        // Project
        $parser->setResult(array());
        $result = $parser->run('/project do something');
        $this->assertArrayHasKey('project', $result);
        $this->assertEquals('project', $result['project']);

        // Customer / Project
        $parser->setResult(array());
        $result = $parser->run('@customer/project do something');
        $this->assertArrayHasKey('project', $result);
        $this->assertEquals('project', $result['project']);
        $this->assertArrayHasKey('customer', $result);
        $this->assertEquals('customer', $result['customer']);

        $parser->setResult(array());
        $result = $parser->run('@customer do something /project');
        $this->assertArrayHasKey('project', $result);
        $this->assertEquals('project', $result['project']);
        $this->assertArrayHasKey('customer', $result);
        $this->assertEquals('customer', $result['customer']);

        // Service
        $parser->setResult(array());
        $result = $parser->run(':service do something');
        $this->assertArrayHasKey('service', $result);
        $this->assertEquals('service', $result['service']);

        // Customer / Service
        $parser->setResult(array());
        $result = $parser->run('@customer:service do something');
        $this->assertArrayHasKey('service', $result);
        $this->assertEquals('service', $result['service']);
        $this->assertArrayHasKey('customer', $result);
        $this->assertEquals('customer', $result['customer']);

        $parser->setResult(array());
        $result = $parser->run('@customer do something :service');
        $this->assertArrayHasKey('service', $result);
        $this->assertEquals('service', $result['service']);
        $this->assertArrayHasKey('customer', $result);
        $this->assertEquals('customer', $result['customer']);

        // Project / Service
        $parser->setResult(array());
        $result = $parser->run('/project :service do something');
        $this->assertArrayHasKey('project', $result);
        $this->assertEquals('project', $result['project']);
        $this->assertArrayHasKey('service', $result);
        $this->assertEquals('service', $result['service']);

        // Customer / Project / Service
        $parser->setResult(array());
        $result = $parser->run('@customer /project :service do something');
        $this->assertArrayHasKey('project', $result);
        $this->assertEquals('project', $result['project']);
        $this->assertArrayHasKey('service', $result);
        $this->assertEquals('service', $result['service']);
        $this->assertArrayHasKey('customer', $result);
        $this->assertEquals('customer', $result['customer']);

        // Tags
        $parser->setResult(array());
        $result = $parser->run('#tag do something');
        $this->assertArrayHasKey('tags', $result);
        $this->assertEquals('tag', $result['tags'][0]);

        $parser->setResult(array());
        $result = $parser->run('#tag #tag1 do something');
        $this->assertArrayHasKey('tags', $result);
        $this->assertEquals('tag', $result['tags'][0]);
        $this->assertEquals('tag1', $result['tags'][1]);

        $result = $parser->run('#tag #tag1 do #tag1 something');
        $this->assertArrayHasKey('tags', $result);
        $this->assertEquals('tag', $result['tags'][0]);
        $this->assertEquals('tag1', $result['tags'][1]);
        $this->assertFalse(isset($result['tags'][2]));

        // Empty
        $parser->setResult(array());
        $result = $parser->run('');
        $this->assertEmpty($result);

    }

    public function testClean()
    {
        $parser = new ActivityRelationParser();
        $input = '@customer do something';

        $parser->run($input);

        $output = $parser->clean($input);

        $this->assertEquals('do something', $output);
    }
}

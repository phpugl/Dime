<?php

namespace Dime\TimetrackerFrontendBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        //$crawler = $client->request('GET', '/hello/Fabien');

        $this->assertTrue(true);
    }
}

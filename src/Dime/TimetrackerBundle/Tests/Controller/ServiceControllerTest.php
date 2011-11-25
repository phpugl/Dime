<?php

namespace Dime\TimetrackerBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ServiceControllerTest extends WebTestCase
{
    public function testGetServicesAction()
    {
        $client = static::createClient();

        // make get request with authentifaction 
        $crawler = $client->request(
            'GET', 
            '/api/services.json', 
            array(), 
            array(),
            array('PHP_AUTH_USER' => 'admin', 'PHP_AUTH_PW' => 'kitten')
        );
        
        // get response
        $response = $client->getResponse();
        
        // convert json to array
        $data = json_decode($response->getContent(), true);
        
        // assert that data has content
        $this->assertTrue(count($data) > 0);
        $this->assertTrue($data[0]['name'] == 'consulting');
    }
}

<?php

namespace Dime\TimetrackerBundle\Tests\Controller;

class ActivitiesControllerTest extends DimeTestCase
{
    public function setUp()
    {
        $this->client = static::createClient();
    }

    public function testAuthentification()
    {
        $this->assertEquals(401, $this->request('GET', '/api/activities', null, array(), array(), array())->getStatusCode());
        $this->assertEquals(200, $this->request('GET', '/api/activities')->getStatusCode());
    }

    public function testGetActivitiesAction()
    {
        $response = $this->request('GET', '/api/activities');
        
        // convert json to array
        $data = json_decode($response->getContent(), true);
        
        // assert that data has content
        $this->assertTrue(count($data) > 0, 'expected to find activities');
        $this->assertEquals($data[0]['description'], 'cwe: initial requirements meeting with customer', 'expected to find "cwe: initial project setup (Symfony2, bundles etc.)');
    }

    public function testGetActivityAction()
    {
        // expect to get 404 on non-existing activity
        $this->assertEquals(404, $this->request('GET', '/api/activities/11111')->getStatusCode());

        // check existing activity
        $response = $this->request('GET', '/api/activities/1');
        
        // convert json to array
        $data = json_decode($response->getContent(), true);
        
        // assert that data has content
        $this->assertTrue(count($data) > 0, 'expected to find activities');
        $this->assertEquals($data['description'], 'cwe: initial requirements meeting with customer', 'expected to find "consulting"');
    }

    public function testPostPutDeleteActivityActions()
    {
        // create new activity
        $response = $this->request('POST', '/api/activities', json_encode(array(
            'description'   => 'Test',
            'rate'          => 65.13,
            'rateReference' => 'customer',
            'service'       => 1,
            'customer'      => 1,
            'project'       => 1,
            'user'          => 1
        )));
        $this->assertEquals(200, $response->getStatusCode());
        
        // convert json to array
        $data = json_decode($response->getContent(), true);

        $id = $data['id'];
        
        // check created activity
        $response = $this->request('GET', '/api/activities/' . $id);
        
        // convert json to array
        $data = json_decode($response->getContent(), true);
        
        // assert that data has content
        $this->assertEquals($data['description'], 'Test', 'expected to find "Test"');
        $this->assertEquals($data['rate'], 65.13, 'expected to find rate "65.13"');

        // modify activity
        $response = $this->request('PUT', '/api/activities/' . $id, json_encode(array(
            'description'   => 'Modified Test',
            'rate'          => 111,
            'service'       => 1,
            'customer'      => 1,
            'project'       => 1,
            'user'          => 1
        )));
        $this->assertEquals(200, $response->getStatusCode());

        $response = $this->request('PUT', '/api/activities/' . ($id+1), json_encode(array(
            'description'   => 'Modified Test',
            'rate'          => 111,
            'service'       => 1,
            'customer'      => 1,
            'project'       => 1,
            'user'          => 1
        )));
        $this->assertEquals(404, $response->getStatusCode());
        
        // check created activity
        $response = $this->request('GET', '/api/activities/' . $id);
        
        // convert json to array
        $data = json_decode($response->getContent(), true);
        
        // assert that data has content
        $this->assertEquals($data['description'], 'Modified Test', 'expected to find "Modified Test"');
        $this->assertEquals($data['rate'], 111, 'expected to find rate "111"');

        // delete activity
        $response = $this->request('DELETE', '/api/activities/' . $id);
        $this->assertEquals(200, $response->getStatusCode());

        // check if activity still exists*/
        $response = $this->request('GET', '/api/activities/' . $id);
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function tearDown()
    {
        parent::tearDown();

        // workaround for https://github.com/symfony/symfony/issues/2531
        if (ob_get_length() == 0 ) {
            ob_start();
        }
    }
}

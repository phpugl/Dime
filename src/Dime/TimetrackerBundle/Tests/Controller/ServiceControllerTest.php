<?php

namespace Dime\TimetrackerBundle\Tests\Controller;

class ServiceControllerTest extends DimeTestCase
{
    public function setUp()
    {
        $this->client = static::createClient();
    }

    public function testAuthentification()
    {
        $this->assertEquals(401, $this->request('GET', '/api/services', null, array(), array(), array())->getStatusCode());
        $this->assertEquals(200, $this->request('GET', '/api/services')->getStatusCode());
    }

    public function testGetServicesAction()
    {
        $response = $this->request('GET', '/api/services');
        
        // convert json to array
        $data = json_decode($response->getContent(), true);
        
        // assert that data has content
        $this->assertTrue(count($data) > 0, 'expected to find services');
        $this->assertEquals($data[0]['name'], 'consulting', 'expected to find "consulting" first');
    }

    public function testGetServiceAction()
    {
        /* expect to get 404 on non-existing service */
        $this->assertEquals(404, $this->request('GET', '/api/services/11111')->getStatusCode());

        /* check existing service */
        $response = $this->request('GET', '/api/services/1');
        
        // convert json to array
        $data = json_decode($response->getContent(), true);
        
        // assert that data has content
        $this->assertTrue(count($data) > 0, 'expected to find services');
        $this->assertEquals($data['name'], 'consulting', 'expected to find "consulting"');
    }

    public function testPostPutDeleteServiceActions()
    {
        /* create new service */
        $response = $this->request('POST', '/api/services', '{"name": "Test", "rate": 555, "foo": "bar"}');
        $this->assertEquals(200, $response->getStatusCode());
        
        // convert json to array
        $data = json_decode($response->getContent(), true);

        $id = $data['id'];
        
        /* check created service */
        $response = $this->request('GET', '/api/services/' . $id . '');
        
        // convert json to array
        $data = json_decode($response->getContent(), true);
        
        // assert that data has content
        $this->assertEquals($data['name'], 'Test', 'expected to find "Test"');
        $this->assertEquals($data['rate'], 555, 'expected to find rate "555"');

        /* modify service */
        $response = $this->request('PUT', '/api/services/' . $id, '{"name": "Modified Test", "rate": 111, "foo": "bar"}');
        $this->assertEquals(200, $response->getStatusCode());

        $response = $this->request('PUT', '/api/services/' . ($id+1), '{"name": "Modified Test", "rate": 111, "foo": "bar"}');
       $this->assertEquals(404, $response->getStatusCode());
        
        /* check created service */
        $response = $this->request('GET', '/api/services/' . $id);
        
        // convert json to array
        $data = json_decode($response->getContent(), true);
        
        // assert that data has content
        $this->assertEquals($data['name'], 'Modified Test', 'expected to find "Modified Test"');
        $this->assertEquals($data['rate'], 111, 'expected to find rate "111"');

        /* delete service */
        $response = $this->request('DELETE', '/api/services/' . $id);
        $this->assertEquals(200, $response->getStatusCode());

        /* check if service still exists*/
        $response = $this->request('GET', '/api/services/' . $id);
        $this->assertEquals(404, $response->getStatusCode());
    }
}

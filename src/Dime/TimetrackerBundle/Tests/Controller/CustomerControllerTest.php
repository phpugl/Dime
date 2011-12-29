<?php

namespace Dime\TimetrackerBundle\Tests\Controller;

class CustomerControllerTest extends DimeTestCase
{
    public function setUp()
    {
        $this->client = static::createClient();
    }

    public function testAuthentification()
    {
        $this->assertEquals(401, $this->request('GET', '/api/customers', array(), array(), array())->getStatusCode());
        $this->assertEquals(200, $this->request('GET', '/api/customers')->getStatusCode());
    }

    public function testGetCustomersAction()
    {
        $response = $this->request('GET', '/api/customers');
        
        // convert json to array
        $data = json_decode($response->getContent(), true);
        
        // assert that data has content
        $this->assertTrue(count($data) > 0, 'expected to find customers');
        $this->assertEquals($data[0]['name'], 'CWE Customer', 'expected to find "CWE Customer" first');
    }

    public function testGetCustomerAction()
    {
        /* expect to get 404 on non-existing service */
        $this->assertEquals(404, $this->request('GET', '/api/customers/11111')->getStatusCode());

        /* check existing service */
        $response = $this->request('GET', '/api/customers/1');
        
        // convert json to array
        $data = json_decode($response->getContent(), true);
        
        // assert that data has content
        $this->assertTrue(count($data) > 0, 'expected to find customers');
        $this->assertEquals($data['name'], 'CWE Customer', 'expected to find "CWE Customer"');
    }

    public function testPostPutDeleteCustomerActions()
    {
        /* create new service */
        $response = $this->request('POST', '/api/customers', array(), array(), null, '{"name": "Test", "alias": "Test"}');
        $this->assertEquals(200, $response->getStatusCode());
        
        // convert json to array
        $data = json_decode($response->getContent(), true);

        $id = $data['id'];
        
        /* check created service */
        $response = $this->request('GET', '/api/customers/' . $id . '');
        
        // convert json to array
        $data = json_decode($response->getContent(), true);
        
        // assert that data has content
        $this->assertEquals($data['name'], 'Test', 'expected to find "Test"');
        $this->assertEquals($data['alias'], 'Test', 'expected to find alias "Test"');

        /* modify service */
        $response = $this->request('PUT', '/api/customers/' . $id . '', array(), array(), null, '{"name": "Modified Test", "alias": "Modified"}');
        $this->assertEquals(200, $response->getStatusCode());

        $response = $this->request('PUT', '/api/customers/' . ($id+1) . '', array(), array(), null, '{"name": "Modified Test", "alias": "Modified"}');
       $this->assertEquals(404, $response->getStatusCode());
        
        /* check created service */
        $response = $this->request('GET', '/api/customers/' . $id . '');
        
        // convert json to array
        $data = json_decode($response->getContent(), true);
        
        // assert that data has content
        $this->assertEquals($data['name'], 'Modified Test', 'expected to find "Modified Test"');
        $this->assertEquals($data['alias'], 'Modified', 'expected to find alias "Modified"');

        /* delete service */
        $response = $this->request('DELETE', '/api/customers/' . $id . '');
        $this->assertEquals(200, $response->getStatusCode());

        /* check if service still exists*/
        $response = $this->request('GET', '/api/customers/' . $id . '');
        $this->assertEquals(404, $response->getStatusCode());
    }
}

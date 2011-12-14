<?php

namespace Dime\TimetrackerBundle\Tests\Controller;

class ProjectControllerTest extends DimeTestCase
{
    public function setUp()
    {
        $this->client = static::createClient();
    }

    public function testAuthentification()
    {
        $this->assertEquals(401, $this->request('GET', '/api/projects', array(), array(), array())->getStatusCode());
        $this->assertEquals(200, $this->request('GET', '/api/projects')->getStatusCode());
    }

    public function testGetProjectsAction()
    {
        $response = $this->request('GET', '/api/projects');
        
        // convert json to array
        $data = json_decode($response->getContent(), true);
        
        // assert that data has content
        $this->assertTrue(count($data) > 0, 'expected to find projects');
        $this->assertEquals($data[0]['name'], 'CWE2011', 'expected to find "CWE2011" first');
    }

    public function testGetProjectAction()
    {
        /* expect to get 404 on non-existing project */
        $this->assertEquals(404, $this->request('GET', '/api/projects/11111')->getStatusCode());

        /* check existing project */
        $response = $this->request('GET', '/api/projects/1');
        
        // convert json to array
        $data = json_decode($response->getContent(), true);
        
        // assert that data has content
        $this->assertTrue(count($data) > 0, 'expected to find projects');
        $this->assertEquals($data['name'], 'CWE2011', 'expected to find "CWE2011"');
    }

    public function testPostPutDeleteProjectActions()
    {
        /* create new project */
        $response = $this->request('POST', '/api/projects', array(), array(), null, json_encode(array(
            'name'          => 'Test',
            'description'   => 'Project test description',
            'rate'          => 55,
            'startedAt'     => '2011-12-05 20:15:00',
            'stoppedAt'     => '2011-12-05 20:45:00',
            'customer'      => 1,
            'user'          => 1,
        )));
        $this->assertEquals(200, $response->getStatusCode());
        
        // convert json to array
        $data = json_decode($response->getContent(), true);

        $projectId = $data['id'];
        
        /* check created project */
        $response = $this->request('GET', '/api/projects/' . $projectId . '');
        
        // convert json to array
        $data = json_decode($response->getContent(), true);
        
        // assert that data has content
        $this->assertEquals($data['name'], 'Test', 'expected to find "Test"');
        $this->assertEquals($data['rate'], 555, 'expected to find rate "555"');

        /* modify project */
        $response = $this->request('PUT', '/api/projects/' . $projectId . '', array(), array(), null, '{"name": "Modified Test", "rate": 111, "foo": "bar"}');
        $this->assertEquals(200, $response->getStatusCode());

        $response = $this->request('PUT', '/api/projects/' . ($projectId+1) . '', array(), array(), null, '{"name": "Modified Test", "rate": 111, "foo": "bar"}');
        $this->assertEquals(404, $response->getStatusCode());
        
        /* check created project */
        $response = $this->request('GET', '/api/projects/' . $projectId . '');
        
        // convert json to array
        $data = json_decode($response->getContent(), true);
        
        // assert that data has content
        $this->assertEquals($data['name'], 'Modified Test', 'expected to find "Modified Test"');
        $this->assertEquals($data['rate'], 111, 'expected to find rate "111"');

        /* delete project */
        $response = $this->request('DELETE', '/api/projects/' . $projectId . '');
        $this->assertEquals(200, $response->getStatusCode());

        /* check if project still exists*/
        $response = $this->request('GET', '/api/projects/' . $projectId . '');
        $this->assertEquals(404, $response->getStatusCode());
    }
}

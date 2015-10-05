<?php

namespace Dime\TimetrackerBundle\Tests\Controller;

class ProjectsControllerTest extends DimeTestCase
{
    public function testAuthentification()
    {
        $this->assertEquals(500, $this->request('GET', '/api/projects', null, array(), array(), array())->getStatusCode());
        $this->loginAs('admin');
        $this->assertEquals(200, $this->request('GET', '/api/projects')->getStatusCode());
    }

    public function testGetProjectsAction()
    {
        $this->loginAs('admin');
        $response = $this->request('GET', '/api/projects');

        // convert json to array
        $data = json_decode($response->getContent(), true);

        // assert that data has content
        $this->assertTrue(count($data) > 0, 'expected to find projects');
        $this->assertEquals('CWE2011', $data[0]['name'], 'expected to find "CWE2011" first');
    }

    public function testGetProjectAction()
    {
        $this->loginAs('admin');
        /* expect to get 404 on non-existing project */
        $this->assertEquals(404, $this->request('GET', '/api/projects/11111')->getStatusCode());

        /* check existing project */
        $response = $this->request('GET', '/api/projects/1');

        // convert json to array
        $data = json_decode($response->getContent(), true);

        // assert that data has content
        $this->assertTrue(count($data) > 0, 'expected to find projects');
        $this->assertEquals('CWE2011', $data['name'], 'expected to find "CWE2011"');
    }

    public function testPostPutDeleteProjectActions()
    {
        $this->loginAs('admin');
        /* create new project */
        $response = $this->request('POST', '/api/projects', json_encode(array(
            'name'          => 'Test',
            'alias'         => 'test',
            'description'   => 'Project test description',
            'rate'          => 555,
            'customer'      => 1,
            'user'          => 1
        )));

        $this->assertEquals(200, $response->getStatusCode());

        // convert json to array
        $data = json_decode($response->getContent(), true);

        $id = $data['id'];

        /* check created project */
        $response = $this->request('GET', '/api/projects/' . $id . '');

        // convert json to array
        $data = json_decode($response->getContent(), true);

        // assert that data has content
        $this->assertEquals('Test', $data['name'], 'expected to find "Test"');
        $this->assertEquals(555, $data['rate'], 'expected to find rate "555"');

        /* modify project */
        $response = $this->request('PUT', '/api/projects/' . $id, json_encode(array(
            'name'          => 'Modified Test',
            'alias'         => 'test',
            'description'   => 'Project test description update',
            'rate'          => 111,
            'customer'      => 1,
            'user'          => 1
        )));
        $this->assertEquals(200, $response->getStatusCode());

        $response = $this->request('PUT', '/api/projects/' . ($id+1), json_encode(array(
            'name'          => 'Modified Test',
            'alias'         => 'test',
            'description'   => 'Project test description update',
            'rate'          => 111,
            'customer'      => 1,
            'user'          => 1
        )));
        $this->assertEquals(404, $response->getStatusCode());

        /* check created project */
        $response = $this->request('GET', '/api/projects/' . $id);

        // convert json to array
        $data = json_decode($response->getContent(), true);

        // assert that data has content
        $this->assertEquals('Modified Test', $data['name'], 'expected to find "Modified Test"');
        $this->assertEquals(111, $data['rate'], 'expected to find rate "111"');

        /* delete project */
        $response = $this->request('DELETE', '/api/projects/' . $id);
        $this->assertEquals(200, $response->getStatusCode());

        /* check if project still exists*/
        $response = $this->request('GET', '/api/projects/' . $id);
        $this->assertEquals(404, $response->getStatusCode());
    }
}

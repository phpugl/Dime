<?php

namespace Dime\TimetrackerBundle\Tests\Controller;

class UsersControllerTest extends DimeTestCase
{
    public function testAuthentification()
    {
        $this->assertEquals(500, $this->request('GET', '/api/users', null, array(), array(), array())->getStatusCode());
        $this->loginAs('admin');
        $this->assertEquals(200, $this->request('GET', '/api/users')->getStatusCode());
    }

    public function testGetUsersAction()
    {
        $this->loginAs('admin');
        $response = $this->request('GET', '/api/users');

        // convert json to array
        $data = json_decode($response->getContent(), true);

        // assert that data has content
        $this->assertTrue(count($data) > 0, 'expected to find users');
        $this->assertEquals($data[0]['firstname'], 'Default', 'expected to find "Default" first');
    }

    public function testGetUserAction()
    {
        $this->loginAs('admin');
        /* expect to get 404 on non-existing service */
        $this->assertEquals(404, $this->request('GET', '/api/users/11111')->getStatusCode());

        /* check existing service */
        $response = $this->request('GET', '/api/users/1');

        // convert json to array
        $data = json_decode($response->getContent(), true);

        // assert that data has content
        $this->assertTrue(count($data) > 0, 'expected to find users');
        $this->assertEquals($data['firstname'], 'Default', 'expected to find "Default"');
    }

    public function testPostPutDeleteUserActions()
    {
        $this->loginAs('admin');
        /* create new service */
        $response = $this->request('POST', '/api/users', '{"username": "test-user", "password": "test", "firstname": "Test", "lastname": "User", "email": "test@user.com"}');
        $this->assertEquals(200, $response->getStatusCode(), $response->getContent());

        // convert json to array
        $data = json_decode($response->getContent(), true);

        $id = $data['id'];

        /* check created service */
        $response = $this->request('GET', '/api/users/' . $id . '');

        // convert json to array
        $data = json_decode($response->getContent(), true);

        // assert that data has content
        $this->assertEquals($data['firstname'], 'Test', 'expected to find "Test"');
        $this->assertEquals($data['email'], "test@user.com", 'expected to find rate "test@user.com"');

        /* modify service */
        $response = $this->request('PUT', '/api/users/' . $id, '{"firstname": "Modified Test", "lastname": "User", "email": "test1@user.com", "foo": "bar"}');
        $this->assertEquals(200, $response->getStatusCode(), $response->getContent());

        $response = $this->request('PUT', '/api/users/' . ($id+1), '{"firstname": "Modified Test", "lastname": "User", "email": "test1@user.com", "foo": "bar"}');
        $this->assertEquals(404, $response->getStatusCode());

        /* check created service */
        $response = $this->request('GET', '/api/users/' . $id);

        // convert json to array
        $data = json_decode($response->getContent(), true);

        // assert that data has content
        $this->assertEquals($data['firstname'], 'Modified Test', 'expected to find "Modified Test"');
        $this->assertEquals($data['email'], "test1@user.com", 'expected to find rate "test1@user.com"');

        /* delete service */
        $response = $this->request('DELETE', '/api/users/' . $id);
        $this->assertEquals(200, $response->getStatusCode(), $response->getContent());

        /* check if service still exists*/
        $response = $this->request('GET', '/api/users/' . $id);
        $this->assertEquals(404, $response->getStatusCode(), $response->getContent());
    }
}

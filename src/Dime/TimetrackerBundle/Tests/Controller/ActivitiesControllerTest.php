<?php

namespace Dime\TimetrackerBundle\Tests\Controller;

class ActivitiesControllerTest extends DimeTestCase
{
    public function testAuthentification()
    {
        $request = $this->request('GET', '/api/activities');
        $this->assertEquals(500, $request->getStatusCode());

        $this->loginas('admin');
        $this->assertEquals(200, $this->request('GET', '/api/activities')->getStatusCode());
    }

    public function testGetActivitiesAction()
    {
        $this->loginas('admin');
        $response = $this->request('GET', '/api/activities');

        // convert json to array
        $data = json_decode($response->getContent(), true);

        // assert that data has content
        $this->assertTrue(count($data) > 0, 'expected to find activities');
        $this->assertEquals('cwe: initial project setup (Symfony2, bundles etc.)', $data[0]['description']);
    }

    public function testGetActivityAction()
    {
        $this->loginas('admin');
        // expect to get 404 on non-existing activity
        $this->assertEquals(404, $this->request('GET', '/api/activities/11111')->getStatusCode());

        // check existing activity
        $response = $this->request('GET', '/api/activities/1');

        // convert json to array
        $data = json_decode($response->getContent(), true);

        // assert that data has content
        $this->assertTrue(count($data) > 0, 'expected to find activities');
        $this->assertEquals('cwe: initial requirements meeting with customer', $data['description'], 'expected to find "consulting"');
    }

    public function testPostPutDeleteActivityActions()
    {
        $this->loginas('admin');
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
        $this->assertEquals('Test', $data['description'], 'expected to find "Test"');
        $this->assertEquals(65.13, $data['rate'], 'expected to find rate "65.13"');

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
        $this->assertEquals('Modified Test', $data['description'], 'expected to find "Modified Test"');
        $this->assertEquals(111, $data['rate'], 'expected to find rate "111"');

        // delete activity
        $response = $this->request('DELETE', '/api/activities/' . $id);
        $this->assertEquals(200, $response->getStatusCode());

        // check if activity still exists*/
        $response = $this->request('GET', '/api/activities/' . $id);
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testPostActivityParsingAction()
    {
        $this->loginas('admin');
        $response = $this->request('POST', '/api/activities', '{"parse": "10:00-12:00 @cc/CWE2011:testing new magic oneline input"}');
        $this->assertEquals(200, $response->getStatusCode(), $response->getContent());
        $content = json_decode($response->getContent(), true);

        $this->assertEquals('new magic oneline input', $content['description']);
        $this->assertEquals('cc', $content['customer']['alias']);
        $this->assertEquals('CWE2011', $content['project']['name']);
        $this->assertEquals('Testing', $content['service']['name']);
        $this->assertEquals(7200, $content['timeslices'][0]['duration']);

        // delete activity
        $response = $this->request('DELETE', '/api/activities/' . $content['id']);
        $this->assertEquals(200, $response->getStatusCode());

        $response = $this->request('POST', '/api/activities', '{"parse": "@cc/CWE2011:testing new magic oneline input"}');
        $this->assertEquals(200, $response->getStatusCode(), $response->getContent());
        $content = json_decode($response->getContent(), true);

        $this->assertEquals('new magic oneline input', $content['description']);
        $this->assertEquals('cc', $content['customer']['alias']);
        $this->assertEquals('CWE2011', $content['project']['name']);
        $this->assertEquals('Testing', $content['service']['name']);
        $this->assertEquals(1, count($content['timeslices']));

        // delete activity
        $response = $this->request('DELETE', '/api/activities/' . $content['id']);
        $this->assertEquals(200, $response->getStatusCode());

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

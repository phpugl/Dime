<?php

namespace Dime\TimetrackerBundle\Tests\Controller;

class TimeslicesControllerTest extends DimeTestCase
{
    public function testAuthentification()
    {
        $this->assertEquals(500, $this->request('GET', '/api/timeslices', null, array(), array(), array())->getStatusCode());
        $this->loginAs('admin');
        $this->assertEquals(200, $this->request('GET', '/api/timeslices')->getStatusCode());
    }

    public function testGetActivitiesTimeSlicesAction()
    {
        $this->loginAs('admin');
        $response = $this->request('GET', '/api/timeslices');

        // convert json to array
        $data = json_decode($response->getContent(), true);

        // assert that data has content
        $this->assertTrue(count($data) > 0, 'expected to find activities timeslices');
        $this->assertEquals($data[0]['duration'], '7200', 'expected to find duration "7200"');
    }

    public function testGetTimesliceAction()
    {
        $this->loginAs('admin');
        // expect to get 404 on non-existing activity
        $this->assertEquals(404, $this->request('GET', '/api/timeslices/11111')->getStatusCode());

        // check existing activity timeslice
        $response = $this->request('GET', '/api/timeslices/1');

        // convert json to array
        $data = json_decode($response->getContent(), true);

        // assert that data has content
        $this->assertTrue(count($data) > 0, 'expected to find activities');
        $this->assertEquals($data['duration'], '7200', 'expected to find duration "7200"');
    }

    public function testPostPutDeleteTimeslicesActions()
    {
        $this->loginAs('admin');
        // create new activity
        $response = $this->request('POST', '/api/timeslices', json_encode(array(
            'activity'    => '1',
            'startedAt'   => '2012-02-10 19:00:00',
            'stoppedAt'   => '2012-02-10 19:30:00',
            'duration'    => ''
        )));
        $this->assertEquals(200, $response->getStatusCode(), $response->getContent());

        // convert json to array
        $data = json_decode($response->getContent(), true);

        $id = $data['id'];

        // check created activity
        $response = $this->request('GET', '/api/timeslices/' . $id);

        // convert json to array
        $data = json_decode($response->getContent(), true);

        // assert that data has content
        $this->assertEquals($data['startedAt'], '2012-02-10 19:00:00', 'expected to find "2012-02-10 19:00:00"');
        $this->assertEquals($data['stoppedAt'], '2012-02-10 19:30:00', 'expected to find rate "2012-02-10 19:30:00"');

        // modify activity
        $response = $this->request('PUT', '/api/timeslices/' . $id, json_encode(array(
            'activity'    => '1',
            'startedAt'   => '2012-02-10 19:00:00',
            'stoppedAt'   => '2012-02-10 19:30:00',
            'duration'    => '7200'
        )));
        $this->assertEquals(200, $response->getStatusCode(), $response->getContent());

        $response = $this->request('PUT', '/api/timeslices/' . $id+200, json_encode(array(
            'activity'    => '1',
            'startedAt'   => '2012-02-10 19:00:00',
            'stoppedAt'   => '2012-02-10 19:30:00',
            'duration'    => ''
        )));
        $this->assertEquals(404, $response->getStatusCode(), $response->getContent());

        // check created activity
        $response = $this->request('GET', '/api/timeslices/' . $id);

        // convert json to array
        $data = json_decode($response->getContent(), true);

        // assert that data has content
        $this->assertEquals($data['duration'], '7200', 'expected to find "7200"');

        // delete activity
        $response = $this->request('DELETE', '/api/timeslices/' . $id);
        $this->assertEquals(200, $response->getStatusCode(), $response->getContent());

        // check if activity still exists*/
        $response = $this->request('GET', '/api/timeslices/' . $id);
        $this->assertEquals(404, $response->getStatusCode(), $response->getContent());
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

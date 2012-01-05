<?php

namespace Dime\TimetrackerBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DimeTestCase extends WebTestCase
{
    public function setUp()
    {
        $this->client = static::createClient();
    }

    /**
     * do a request using HTTP authentification 
     */
    protected function request(
        $method,
        $url,
        $content = null,
        $parameters = array(), 
        $files = array(),
        $server = null
    ) {
        if (is_null($server)) {
            $server = array('PHP_AUTH_USER' => 'admin', 'PHP_AUTH_PW' => 'kitten');
        }
        $this->client->restart();

        // make get request with authentifaction 
        $this->client->request($method, $url, $parameters, $files, $server, $content);
        return $this->client->getResponse();
    }
}

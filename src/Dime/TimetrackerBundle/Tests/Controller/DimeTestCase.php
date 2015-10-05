<?php

namespace Dime\TimetrackerBundle\Tests\Controller;

use Symfony\Component\BrowserKit\Cookie;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class DimeTestCase extends WebTestCase
{
    /* @var $client Client */
    protected $client;
    const FIREWALL_NAME = 'main';

    public function setUp()
    {
        $this->client = self::createClient();
    }

    protected function request(
        $method,
        $uri,
        $content = null,
        array $parameters = array(),
        array $files = array(),
        array $server = array(),
        $changeHistory = true
    )
    {
        $this->client->request($method, $uri, $parameters, $files, $server, $content, $changeHistory);
        return $this->client->getResponse();
    }

    /**
     * User with auth.
     *
     * @param $firewallName
     * @param array $options
     * @param array $server
     *
     * @return Client
     */
    protected function loginAs($user, $password = null)
    {
        $this->client->restart();

        $container = $this->client->getContainer();

        if ($user) {
            $session = $container->get('session');
            /** @var $userManager \FOS\UserBundle\Doctrine\UserManager */
            $userManager = $container->get('fos_user.user_manager');
            /** @var $loginManager \FOS\UserBundle\Security\LoginManager */
            $loginManager = $container->get('fos_user.security.login_manager');
            $firewallName = $container->getParameter('fos_user.firewall_name');

            $user = $userManager->findUserBy(array('username' => $user));
            $loginManager->loginUser($firewallName, $user);

            // save the login token into the session and put it in a cookie
            $container->get('session')->set('_security_' . $firewallName, serialize($container->get('security.context')->getToken()));
            $container->get('session')->save();
            $this->client->getCookieJar()->set(new Cookie($session->getName(), $session->getId()));
        }
    }
}

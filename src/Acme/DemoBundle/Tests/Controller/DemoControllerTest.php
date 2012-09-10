<?php

namespace Acme\DemoBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class DemoControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/demo/hello/Fabien');

        $this->assertTrue($crawler->filter('html:contains("Hello Fabien")')->count() > 0);
    }

    public function testFailure()
    {
        $client = static::createClient();
        $container = static::$kernel->getContainer();

        $client->getCookieJar()->set(new Cookie($container->get('session')->getName(), true));
        $token = new UsernamePasswordToken('admin', null, 'secured_area', array('ROLE_ADMIN'));
        $container->get('security.context')->setToken($token);
        $container->get('session')->set('_security_secured_area', serialize($token));

        $client->request('GET', '/demo/secured/hello/Fabien');

        $this->assertTrue($client->getResponse()->isSuccessful());
    }
}

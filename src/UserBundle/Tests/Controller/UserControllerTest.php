<?php

namespace UserBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testAuth()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/auth');
    }

}

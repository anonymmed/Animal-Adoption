<?php

namespace TemplateBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TemplateControllerTest extends WebTestCase
{
    public function testLayout()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/layout');
    }

}

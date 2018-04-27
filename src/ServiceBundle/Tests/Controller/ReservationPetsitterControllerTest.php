<?php

namespace ServiceBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ReservationPetsitterControllerTest extends WebTestCase
{
    public function testReserverpetsitter()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/reserverPetsitter');
    }

}

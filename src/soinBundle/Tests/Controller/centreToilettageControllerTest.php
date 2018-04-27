<?php

namespace soinBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class centreToilettageControllerTest extends WebTestCase
{
    public function testAjoutercentretoilettage()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/ajouterCentreToilettage');
    }

    public function testModifiercentretoilettage()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/modifierCentreToilettage');
    }

    public function testSupprimercentretilettage()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/supprimerCentreTilettage');
    }

    public function testAffichercentretoilettage()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/afficherCentreToilettage');
    }

}

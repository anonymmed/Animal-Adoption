<?php

namespace ServiceBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CentreDressageControllerTest extends WebTestCase
{
    public function testAjoutercentredressage()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/ajouterCentreDressage');
    }

    public function testModifiercentredressage()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/modifierCentreDressage');
    }

    public function testSupprimercentredressage()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/supprimerCentreDressage');
    }

    public function testAffichercentredressage()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/afficherCentreDressage');
    }

}

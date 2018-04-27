<?php

namespace VenteBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProduitControllerTest extends WebTestCase
{
    public function testAjouterproduit()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/ajouterProduit');
    }

    public function testModifierproduit()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/modifierProduit');
    }

    public function testSupprimerproduit()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/supprimerProduit');
    }

    public function testAfficherproduit()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/afficherProduit');
    }

}

<?php

namespace AdoptionBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AnimalControllerTest extends WebTestCase
{
    public function testAjouter()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/ajouter');
    }

    public function testSupprimer()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/supprimer');
    }

    public function testAfficher()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/afficher');
    }

    public function testModifier()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/modifier');
    }

}

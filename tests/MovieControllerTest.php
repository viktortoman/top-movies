<?php

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MovieControllerTest extends WebTestCase
{
    public function testList(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/movie/list');

        $this->assertResponseIsSuccessful();

        $response = $client->getResponse();
        $data = $response->getContent();

        $this->assertStringContainsString("results", $data);
    }
}
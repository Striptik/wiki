<?php

namespace Wiki\WikiBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testGetusers()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/users');
    }

    public function testGetuser()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/users/{user_id}');
    }

    public function testPostusers()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/users');
    }

    public function testRemoveuser()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/users/{user_id}');
    }

    public function testUpdateuser()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/users/{user_id}');
    }

    public function testPatchuser()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/users/{user_id}');
    }

}

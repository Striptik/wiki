<?php

namespace Wiki\WikiBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PageControllerTest extends WebTestCase
{
    public function testGetpages()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/pages');
    }

}

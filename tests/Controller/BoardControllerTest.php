<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BoardControllerTest extends WebTestCase
{
    public function testBoardSidebar(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/board_sidebar');

        $this->assertResponseIsSuccessful();
        $this->assertTrue($crawler->filter('li')->count() > 0);
    }
}

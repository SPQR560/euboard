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

    public function testBoardThreads(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/board/b');

        $this->assertResponseIsSuccessful();
        $textHtml = $crawler->filter('.col-4')->first()->filter('h1 > a')->html();
        $this->assertEquals('How are you?', $textHtml);
    }
}

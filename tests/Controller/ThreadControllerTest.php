<?php

namespace App\Tests\Controller;

use App\Model\Thread\Entity\Thread;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ThreadControllerTest extends WebTestCase
{

    public function testGetThread(): void
    {
        $client = static::createClient();

        $kernel = self::bootKernel();
        $em = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        $treadRepository = $em->getRepository(Thread::class);
        $thread = $treadRepository->findOneBy(['name' => 'How are you?']);

        $crawler = $client->request('GET', '/thread/get/' . htmlspecialchars($thread->getid()));

        $this->assertResponseIsSuccessful();
        $this->assertEquals(4, $crawler->filter('main')->children('.container')->count());
    }

    public function testCreateThread(): void
    {
        $client = static::createClient();

        $client->request('GET', '/board/b');
        $this->assertResponseIsSuccessful();

        $client->clickLink('add thread');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'create a new thread');

        $messageText = 'Test test test';
        $client->submitForm('Submit', [
            'thread_form[name]' => 'new thread',
            'thread_form[text]' => $messageText,
        ]);
       $this->assertResponseRedirects();
       $crawler = $client->followRedirect();
       $firstMessageAtThread = $crawler->filter('main')
           ->children('.container')
           ->filter('p')
           ->text();
       $this->assertEquals($messageText, $firstMessageAtThread);
    }
}

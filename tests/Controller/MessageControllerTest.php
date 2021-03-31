<?php

namespace App\Tests\Controller;

use App\Model\Thread\Entity\Thread;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MessageControllerTest extends WebTestCase
{
    protected function getThreadWithNameHowAreYou(): Thread
    {
        $kernel = self::bootKernel();
        $em = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        $treadRepository = $em->getRepository(Thread::class);
        return $treadRepository->findOneBy(['name' => 'How are you?']);
    }

    public function testAddMessage(): void
    {
        $client = static::createClient();

        $thread = $this->getThreadWithNameHowAreYou();

        $client->request('GET', '/thread/get/' . htmlspecialchars($thread->getid()));
        $this->assertResponseIsSuccessful();

        $messageText = 'Test test test';
        $client->submitForm('Submit', [
            'message_form[text]' => $messageText,
        ]);
        $this->assertResponseRedirects();
        $crawler = $client->followRedirect();

        $lastMessageAtThread = $crawler->filter('main')->children('.mt-1')->last();
        $lastMessageText = $lastMessageAtThread->filter('p')->text();
        $lastMessageId = str_replace('#', '>>>>>', $lastMessageAtThread->filter('strong')->text());
        $this->assertEquals($messageText, $lastMessageText);

        $client->submitForm('Submit', [
            'message_form[text]' => "$lastMessageId Hello",
        ]);
        $this->assertResponseRedirects();
        $crawler = $client->followRedirect();

        $lastMessageAtThread = $crawler->filter('main')->children('.mt-1')->last();
        $lastMessageText = $lastMessageAtThread->filter('p')->text();
        $lastMessageParentId = $lastMessageAtThread->filter('a.board-message')->text();

        $this->assertEquals('Hello', $lastMessageText);
        $this->assertEquals($lastMessageId, $lastMessageParentId);
    }
}

<?php

namespace App\Tests\Controller;

use App\Model\Thread\Entity\Thread;
use App\Model\Thread\Repository\ThreadRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ThreadControllerTest extends WebTestCase
{
    private EntityManager $em;

    public function testThread(): void
    {
        $client = static::createClient();

        $kernel = self::bootKernel();
        $this->em = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        $treadRepository = $this->em->getRepository(Thread::class);
        $thread = $treadRepository->findOneBy(['name' => 'How are you?']);

        $crawler = $client->request('GET', '/thread/'. htmlspecialchars($thread->getid()));

        $this->assertResponseIsSuccessful();
        $this->assertTrue($crawler->filter('main')->children('.container')->count() == 4);
    }
}

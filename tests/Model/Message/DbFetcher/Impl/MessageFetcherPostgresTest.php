<?php

namespace App\Tests\Model\Message\DbFetcher\Impl;

use App\Model\Message\DbFetcher\MessageFetcherPostgres;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MessageFetcherPostgresTest extends KernelTestCase
{
    protected Connection $connection;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->connection = $kernel->getContainer()->get('doctrine.dbal.default_connection');
    }

    public function testGetMessages(): void
    {
        $messageFetcher = new MessageFetcherPostgres($this->connection);
        $treadId = $this->getHowAreYouThreadId();

        $messages = $messageFetcher->getMessages($treadId);
        $foundByChildMessageColumn = array_filter($messages, function ($m) {
            return '23 24' === $m['child_massages'];
        });

        $this->assertTrue(count($messages) > 0);
        $this->assertCount(1, $foundByChildMessageColumn);
    }

    private function getHowAreYouThreadId(): int
    {
        $sql = $this->connection->createQueryBuilder()
            ->select('t.id as id')
            ->from('thread', 't')
            ->where("t.name = 'How are you?'")
            ->getSQL();

        $result = $this->connection->executeQuery($sql);
        $array = $result->fetchAssociative();

        return $array['id'];
    }
}

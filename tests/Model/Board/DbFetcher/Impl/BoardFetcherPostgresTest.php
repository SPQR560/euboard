<?php

namespace App\Tests\Model\Board\DbFetcher\Impl;

use App\Model\Board\DbFetcher\Impl\BoardFetcherPostgres;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class BoardFetcherPostgresTest extends KernelTestCase
{
    protected Connection $connection;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->connection = $kernel->getContainer()->get('doctrine.dbal.default_connection');
    }

    public function testGetBoard(): void
    {
        $boardFetcher = new BoardFetcherPostgres($this->connection);

        $boards = $boardFetcher->getBoards();

        $this->assertTrue(count($boards) > 0);
    }

    public function testGetBoardByName(): void
    {
        $boardFetcher = new BoardFetcherPostgres($this->connection);

        $boards = $boardFetcher->getBoards('rand');

        $this->assertTrue(count($boards) == 1);
        $this->assertEquals("Random", $boards[0]['name']);
    }

    public function testGetBoardSorting(): void
    {
        $boardFetcher = new BoardFetcherPostgres($this->connection);

        $boards = $boardFetcher->getBoards('', true);
        $this->assertEquals("Random", $boards[0]['name']);

        $boards = $boardFetcher->getBoards('', false);
        $this->assertEquals("Random", $boards[count($boards) - 1]['name']);
    }

    public function testGetBoardPostPerHourRating(): void
    {
        $boardFetcher = new BoardFetcherPostgres($this->connection);
        $oldestThreadCreationTime = new \DateTimeImmutable($this->getOldestThreadOnRandomBoard());
        $boards = $boardFetcher->getBoards('', true, $oldestThreadCreationTime);
        $this->assertEquals(0, (float)$boards[0]['postperhour']);

        $boards = $boardFetcher->getBoards('', true, $oldestThreadCreationTime->add(new \DateInterval('PT1H')));
        $this->assertEquals(4.00, (float)$boards[0]['postperhour']);

        $boards = $boardFetcher->getBoards('', true, $oldestThreadCreationTime->add(new \DateInterval('PT1H30M')));
        $this->assertEquals(2.67, (float)$boards[0]['postperhour']);

        $boards = $boardFetcher->getBoards('', true, $oldestThreadCreationTime->add(new \DateInterval('PT10H')));
        $this->assertEquals(0.4, (float)$boards[0]['postperhour']);

        $boards = $boardFetcher->getBoards('', true, $oldestThreadCreationTime->add(new \DateInterval('PT10S')));
        $this->assertEquals(4, (float)$boards[0]['postperhour']);
    }

    private function getOldestThreadOnRandomBoard():string
    {
        $sql = $this->connection->createQueryBuilder()
            ->select('MIN(t.creation_time) as time')
            ->from('thread', 't')
            ->leftJoin('t','board', 'b', 't.board_id = b.id')
            ->where("b.name = 'Random'")
            ->groupBy('t.board_id')
            ->getSQL();

        $result = $this->connection->executeQuery($sql);
        $array = $result->fetchAssociative();
        return $array['time'];
    }
}

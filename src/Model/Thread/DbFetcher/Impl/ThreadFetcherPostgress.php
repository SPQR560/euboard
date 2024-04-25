<?php

declare(strict_types=1);

namespace App\Model\Thread\DbFetcher\Impl;

use App\Model\Thread\DbFetcher\IThreadFetcher;
use Doctrine\DBAL\Connection;

class ThreadFetcherPostgress implements IThreadFetcher
{
    protected $connection;

    /**
     * MessageFetcherPostgres constructor.
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws \Doctrine\DBAL\Exception
     */
    public function getThreads(int $boardId): array
    {
        $currentTime = new \DateTimeImmutable();

        $sql = $this->getSql();
        $result = $this->connection->executeQuery($sql, [
            'boardId' => $boardId,
            'currentTime' => $currentTime->format('Y-m-d H:i:sO'),
        ]);

        return $result->fetchAllAssociative();
    }

    protected function getSql(): string
    {
        return <<<SQL
SELECT
    t.id,
    t.board_id,
    t.author_id,
    t.name,
    t.picture,
    t.picture_name,
    t.creation_time,
    t.text,
    td.messengesOnThreadCount / (EXTRACT(epoch FROM (:currentTime - td.threadCreationTime)) / 3600) as treadRate
FROM thread as t
INNER JOIN
(SELECT t.creation_time AS threadCreationTime,
       count(m.id) AS messengesOnThreadCount,
       t.id
FROM board as b
LEFT JOIN THREAD AS t ON b.id = t.board_id
LEFT JOIN message m ON t.id = m.thread_id
WHERE b.id = :boardId
GROUP BY t.id) as td ON t.id = td.id
ORDER BY treadRate DESC;
SQL;
    }
}

<?php
declare(strict_types=1);

namespace App\Model\Board\DbFetcher\Impl;

use App\Model\Board\DbFetcher\IBoardFetcher;
use Doctrine\DBAL\Connection;

class BoardFetcherPostgres implements  IBoardFetcher
{
    protected $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param \DateTimeImmutable $currentTime
     * @param string $boardName
     * @param bool $sortDesc
     * @return array
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws \Doctrine\DBAL\Exception
     */
    function getBoards(string $boardName = '', bool $sortDesc = true, \DateTimeImmutable $currentTime = null): array
    {
        if (is_null($currentTime)) {
            $currentTime = new \DateTimeImmutable();
        }

        $sql = $this->getBoardsWithMessagePerHourRate($sortDesc);
        $result = $this->connection->executeQuery($sql, [
            'name' => $boardName,
            'currentTime' => $currentTime->format("Y-m-d H:i:sO")
        ]);
        return $result->fetchAllAssociative();
    }

    /**
     * @param bool $sortDesc
     * @return string
     */
    protected function getBoardsWithMessagePerHourRate(bool $sortDesc = true): string
    {
        return "SELECT query.name,
                   query.path,
                   CASE
                       WHEN query.countOfHours=0
                            OR query.countOfHours IS NULL
                            OR query.messengesOnThreadCount IS NULL THEN 0
                       ELSE round((query.messengesOnThreadCount / query.countOfHours)::numeric, 2)
                   END AS postPerHour
            FROM
              (SELECT b.name AS name,
                      b.path AS PATH,
                      EXTRACT(epoch
                             --FROM (m.lastMessageOnBoardTime - tr.oldestThreadCreationTime))/3600 AS countOfHours,
                               FROM (:currentTime - tr.oldestThreadCreationTime))/3600 AS countOfHours,
                      m.messengesOnThreadCount AS messengesOnThreadCount
               FROM board AS b
               LEFT JOIN
                 (SELECT min(t.creation_time) AS oldestThreadCreationTime,
                         t.board_id
                  FROM THREAD AS t
                  GROUP BY t.board_id) AS tr ON tr.board_id = b.id
               LEFT JOIN
                 (SELECT max(m.time) AS lastMessageOnBoardTime,
                         count(m.id) AS messengesOnThreadCount,
                         t.board_id
                  FROM THREAD AS t
                  LEFT JOIN message m ON t.id = m.thread_id
                  GROUP BY t.board_id) AS m ON m.board_id = b.id
               WHERE LOWER(b.name) LIKE '%'||:name||'%' ) AS query
               ORDER BY postPerHour " . ($sortDesc ? "DESC;" : "ASC;");
    }
}
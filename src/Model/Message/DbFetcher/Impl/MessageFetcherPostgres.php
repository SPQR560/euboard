<?php


namespace App\Model\Message\DbFetcher\Impl;


use App\Model\Message\DbFetcher\IMessageFetcher;
use Doctrine\DBAL\Connection;

class MessageFetcherPostgres implements IMessageFetcher
{
    protected $connection;

    /**
     * MessageFetcherPostgres constructor.
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return array
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws \Doctrine\DBAL\Exception
     */
    public function getMessages(int $threadId): array
    {
        $sql = $this->getSql();
        $result = $this->connection->executeQuery($sql, [
            'tread_id' => $threadId
        ]);
        return $result->fetchAllAssociative();
    }

    /**
     * @return string
     */
    protected function getSql(): string
    {
        return "SELECT m.id,
                       m.author_id,
                       m.text,
                       m.time,
                       responded_to.parent_massages,
                       answers.child_massages
                FROM message m
                LEFT JOIN
                  (SELECT child_message.id AS child_message,
                          STRING_AGG(parent_massage.id::CHARACTER varying, ' ') AS parent_massages
                   FROM message AS child_message
                   INNER JOIN child_messages cm ON child_message.id = cm.message_id
                   INNER JOIN message parent_massage ON parent_massage.id = cm.parent_message_id
                   WHERE cm.thread_id = :tread_id
                     AND child_message.thread_id = :tread_id
                     AND parent_massage.thread_id = :tread_id
                   GROUP BY child_message.id) AS responded_to ON responded_to.child_message = m.id
                LEFT JOIN
                  (SELECT parent_message.id AS parent_message,
                          STRING_AGG(child_massage.id::CHARACTER varying, ' ') AS child_massages
                   FROM message AS parent_message
                   INNER JOIN child_messages cm ON parent_message.id = cm.parent_message_id
                   INNER JOIN message child_massage ON child_massage.id = cm.message_id
                   WHERE cm.thread_id = :tread_id
                     AND parent_message.thread_id = :tread_id
                     AND child_massage.thread_id = :tread_id
                   GROUP BY parent_message.id) AS answers ON answers.parent_message = m.id
                WHERE m.thread_id = :tread_id";
    }
}
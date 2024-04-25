<?php

declare(strict_types=1);

namespace App\Model\Message\DbFetcher;

interface IMessageFetcher
{
    public function getMessages(int $threadId): array;
}

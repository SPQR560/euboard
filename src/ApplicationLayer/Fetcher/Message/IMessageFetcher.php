<?php

declare(strict_types=1);

namespace App\ApplicationLayer\Fetcher\Message;

interface IMessageFetcher
{
    public function getMessages(int $threadId): array;
}

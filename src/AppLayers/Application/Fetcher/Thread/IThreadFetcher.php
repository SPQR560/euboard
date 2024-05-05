<?php

declare(strict_types=1);

namespace App\AppLayers\Application\Fetcher\Thread;

interface IThreadFetcher
{
    public function getThreads(int $boardId): array;
}

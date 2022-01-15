<?php
declare(strict_types=1);

namespace App\Model\Thread\DbFetcher;

interface IThreadFetcher
{
    public function getThreads(int $boardId);
}

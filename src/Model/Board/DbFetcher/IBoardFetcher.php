<?php

namespace App\Model\Board\DbFetcher;

interface IBoardFetcher
{
    public function getBoards(string $boardName = '', bool $sortDesc = true, \DateTimeImmutable $currentTime = null): array;
}

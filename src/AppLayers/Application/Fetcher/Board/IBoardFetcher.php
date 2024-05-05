<?php

namespace App\AppLayers\Application\Fetcher\Board;

interface IBoardFetcher
{
    public function getBoards(string $boardName = '', bool $sortDesc = true, \DateTimeImmutable $currentTime = null): array;
}

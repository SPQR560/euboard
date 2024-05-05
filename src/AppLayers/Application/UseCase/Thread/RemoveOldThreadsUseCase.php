<?php

declare(strict_types=1);

namespace App\AppLayers\Application\UseCase\Thread;

use App\Model\Thread\Repository\ThreadRepository;

class RemoveOldThreadsUseCase
{
    private ThreadRepository $threadRepository;

    public function __construct(ThreadRepository $threadRepository)
    {
        $this->threadRepository = $threadRepository;
    }


    public function removeOldThreads(): void
    {
        $this->threadRepository->deleteOldThreads();
    }
}
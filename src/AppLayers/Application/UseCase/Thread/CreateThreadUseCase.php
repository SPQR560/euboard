<?php

declare(strict_types=1);

namespace App\AppLayers\Application\UseCase\Thread;

use App\AppLayers\Domain\Exception\BoardIsNotFountException;
use App\Model\Board\Repository\BoardRepository;
use App\Model\Thread\Entity\Thread;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class CreateThreadUseCase
{
    private EntityManagerInterface $entityManager;

    private BoardRepository $boardRepository;

    private Security $security;

    public function __construct(
        EntityManagerInterface $entityManager,
        BoardRepository $boardRepository,
        Security $security
    ) {
        $this->entityManager = $entityManager;
        $this->boardRepository = $boardRepository;
        $this->security = $security;
    }

    /**
     * @throws BoardIsNotFountException
     */
    public function createThread(Thread $thread, int $boardId): void
    {
        $board = $this->boardRepository->findOneBy(['id' => $boardId]);
        if ($board === null) {
            throw new BoardIsNotFountException();
        }

        $thread->setCreationTime(new \DateTimeImmutable());
        $thread->setBoard($board);
        //todo add picture
        $user = $this->security->getUser();
        if (!is_null($user)) {
            $thread->setAuthor($user);
        }

        $this->entityManager->persist($thread);
        $this->entityManager->flush();
    }
}
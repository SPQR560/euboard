<?php

declare(strict_types=1);

namespace App\AppLayers\Application\UseCase\Message;

use App\Model\Message\Entity\Message;
use App\Model\Message\Repository\ChildMessagesRepository;
use Doctrine\ORM\EntityManagerInterface;

class DeleteMessageUseCase
{
    private EntityManagerInterface $entityManager;

    private ChildMessagesRepository $childMessagesRepository;

    public function __construct(
        ChildMessagesRepository $childMessagesRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->childMessagesRepository = $childMessagesRepository;
        $this->entityManager = $entityManager;
    }

    public function deleteMessage(Message $message): void
    {
        $this->entityManager->remove($message);

        $parentMessages = $this->childMessagesRepository->findBy(['message' => $message]);
        foreach ($parentMessages as $parentMessage) {
            $this->entityManager->remove($parentMessage);
        }

        $childMessages = $this->childMessagesRepository->findBy(['parentMessage' => $message]);
        foreach ($childMessages as $childMessage) {
            $this->entityManager->remove($childMessage);
        }

        $this->entityManager->flush();
    }
}

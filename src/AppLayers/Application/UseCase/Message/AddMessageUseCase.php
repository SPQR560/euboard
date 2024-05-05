<?php

declare(strict_types=1);

namespace App\AppLayers\Application\UseCase\Message;

use App\AppLayers\Domain\Service\MessageTextHandler;
use App\Model\Message\Entity\ChildMessages;
use App\Model\Message\Entity\Message;
use App\Model\Message\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class AddMessageUseCase
{
    private MessageTextHandler $textHandler;
    private Security $security;
    private EntityManagerInterface $entityManager;
    private MessageRepository $messageRepository;

    public function __construct(
        MessageTextHandler $textHandler,
        Security $security,
        MessageRepository $messageRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->textHandler = $textHandler;
        $this->security = $security;
        $this->entityManager = $entityManager;
        $this->messageRepository = $messageRepository;
    }

    public function addMessage(Message $message): void
    {
        $message->setTime(new \DateTimeImmutable());
        $resultArray = $this->textHandler->handleMessage($message);
        $message = $resultArray['message'];

        //todo add picture
        $user = $this->security->getUser();
        if (!is_null($user)) {
            $message->setAuthor($user);
        }

        $this->entityManager->persist($message);

        foreach ($resultArray['listOfParentMessages'] as $parentMessageId) {
            $parentMessage = $this->messageRepository->findOneBy(['id' => $parentMessageId]);
            //todo rename entity
            $childMessage = new ChildMessages($message, $parentMessage, $parentMessage->getThread());
            $this->entityManager->persist($childMessage);
        }

        $this->entityManager->flush();
    }
}
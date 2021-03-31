<?php

namespace App\Controller;

use App\Form\MessageFormType;
use App\Model\Message\Entity\ChildMessages;
use App\Model\Message\Entity\Message;
use App\Model\Message\Repository\MessageRepository;
use App\Model\Message\Service\MessageTextHandler;
use App\Model\Thread\Repository\ThreadRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{
    protected EntityManagerInterface $entityManager;
    protected ThreadRepository $threadRepository;
    protected MessageTextHandler $textHandler;
    protected MessageRepository $messageRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        ThreadRepository $threadRepository,
        MessageTextHandler $textHandler,
        MessageRepository $messageRepository
    ) {
        $this->entityManager = $entityManager;
        $this->threadRepository = $threadRepository;
        $this->textHandler = $textHandler;
        $this->messageRepository = $messageRepository;
    }

    /**
     * @Route("/message/add", name="add_message", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function addMessage(Request $request): Response
    {
        $message = new Message();
        $form = $this->createForm(MessageFormType::class, $message);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $message->setTime(new \DateTimeImmutable());
            $resultArray = $this->textHandler->handleMessage($message);
            $message = $resultArray['message'];

            //todo add author and picture
            $this->entityManager->persist($message);

            foreach ($resultArray['listOfParentMessages'] as $parentMessageId) {
                $parentMessage = $this->messageRepository->findOneBy(['id' => $parentMessageId]);
                //todo rename entity
                $childMessage = new ChildMessages($message, $parentMessage, $parentMessage->getThread());
                $this->entityManager->persist($childMessage);
            }

            $this->entityManager->flush();
        }

        return $this->redirectToRoute('get_thread', ['id' => $message->getThread()->getId()]);
    }
}

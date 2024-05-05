<?php

declare(strict_types=1);

namespace App\Controller;

use App\ApplicationLayer\UseCase\Message\AddMessageUseCase;
use App\ApplicationLayer\UseCase\Message\DeleteMessageUseCase;
use App\Form\MessageFormType;
use App\Model\Message\Entity\Message;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class MessageController extends AbstractController
{
    private AddMessageUseCase $addMessageUseCase;
    private DeleteMessageUseCase $deleteMessageUseCase;

    public function __construct(
        AddMessageUseCase $addMessageUseCase,
        DeleteMessageUseCase $deleteMessageUseCase
    ) {
        $this->addMessageUseCase = $addMessageUseCase;
        $this->deleteMessageUseCase = $deleteMessageUseCase;
    }

    /**
     * @Route("/message/add", name="add_message", methods={"POST"})
     */
    public function addMessage(Request $request): Response
    {
        $message = new Message();
        $form = $this->createForm(MessageFormType::class, $message);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->addMessageUseCase->addMessage($message);
        }

        return $this->redirectToRoute('get_thread', ['id' => $message->getThread()->getId()]);
    }

    /**
     * @Route("/message/delete/{id}", name="delete_message", methods={"POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function deleteMessage(Message $message): Response
    {
        $treadId = $message->getThread()->getId();

        $this->deleteMessageUseCase->deleteMessage($message);

        return $this->redirectToRoute('get_thread', ['id' => $treadId]);
    }
}

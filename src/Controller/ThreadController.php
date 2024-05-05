<?php

namespace App\Controller;

use App\AppLayers\Application\Fetcher\Message\IMessageFetcher;
use App\AppLayers\Application\UseCase\Thread\CreateThreadUseCase;
use App\AppLayers\Domain\Exception\BoardIsNotFountException;
use App\Form\MessageFormType;
use App\Form\ThreadFormType;
use App\Model\Message\Entity\Message;
use App\Model\Thread\Entity\Thread;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ThreadController extends AbstractController
{
    private IMessageFetcher $messageFetcher;

    private CreateThreadUseCase $createThreadUseCase;

    public function __construct(
        IMessageFetcher $messageFetcher,
        CreateThreadUseCase $createThreadUseCase
    ) {
        $this->messageFetcher = $messageFetcher;
        $this->createThreadUseCase = $createThreadUseCase;
    }

    /**
     * @Route("/thread/get/{id}", name="get_thread")
     */
    public function getThread(Thread $thread): Response
    {
        $messages = $this->messageFetcher->getMessages($thread->getId());

        $message = new Message();
        $message->setThread($thread);

        $form = $this->createForm(MessageFormType::class, $message, [
            'action' => $this->generateUrl('add_message'),
        ]);

        return $this->render('thread/getThread.html.twig', [
            'thread' => $thread,
            'messages' => $messages,
            'message_form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/thread/add-thread/", name="add_thread")
     */
    public function createThread(Request $request): Response
    {
        $thread = new Thread();
        $form = $this->createForm(ThreadFormType::class, $thread);

        $form->handleRequest($request);
        try {
            if ($form->isSubmitted() && $form->isValid()) {
                $this->createThreadUseCase->createThread($thread, $request->get('board-id'));

                return $this->redirectToRoute('get_thread', ['id' => $thread->getId()]);
            }
        } catch (BoardIsNotFountException $boardIsNotFountException) {
            $form->get('text')->addError(new FormError('board is not found'));
        }

        return $this->render('thread/addThread.html.twig', [
            'thread_form' => $form->createView(),
        ]);
    }
}

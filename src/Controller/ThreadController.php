<?php

namespace App\Controller;

use App\Model\Message\DbFetcher\IMessageFetcher;
use App\Model\Thread\Entity\Thread;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ThreadController extends AbstractController
{
    protected IMessageFetcher $messageFetcher;

    public function __construct(IMessageFetcher $messageFetcher)
    {
        $this->messageFetcher = $messageFetcher;
    }

    /**
     * @Route("/thread/{id}", name="thread")
     * @param Thread $thread
     * @return Response
     */
    public function thread(Thread $thread): Response
    {
        $messages = $this->messageFetcher->getMessages($thread->getId());

        return $this->render('thread/thread.html.twig', [
            'thread' => $thread,
            'messages' => $messages
        ]);
    }
}

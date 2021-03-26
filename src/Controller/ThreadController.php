<?php

namespace App\Controller;

use App\Form\ThreadFormType;
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
     * @Route("/thread/get/{id}", name="get_thread")
     * @param Thread $thread
     * @return Response
     */
    public function getThread(Thread $thread): Response
    {
        $messages = $this->messageFetcher->getMessages($thread->getId());

        return $this->render('thread/getThread.html.twig', [
            'thread' => $thread,
            'messages' => $messages
        ]);
    }
//
//    /**
//     * @Route("/thread/add-thread/", name="add_thread")
//     * @return Response
//     */
//    public function createThread(): Response
//    {
//        $thread = new Thread();
//        $form = $this->createForm(ThreadFormType::class, $thread);
//
//        return $this->render('thread/addThread.html.twig', [
//            'thread_form' => $form->createView()
//        ]);
//    }
}

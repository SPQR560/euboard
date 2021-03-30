<?php

namespace App\Controller;

use App\Form\MessageFormType;
use App\Form\ThreadFormType;
use App\Model\Board\Repository\BoardRepository;
use App\Model\Message\DbFetcher\IMessageFetcher;
use App\Model\Message\Entity\Message;
use App\Model\Message\Service\MessageTextHandler;
use App\Model\Thread\Entity\Thread;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ThreadController extends AbstractController
{
    protected IMessageFetcher $messageFetcher;
    protected EntityManagerInterface $entityManager;
    protected BoardRepository $boardRepository;

    public function __construct(
        IMessageFetcher $messageFetcher,
        EntityManagerInterface $entityManager,
        BoardRepository $boardRepository
    ) {
        $this->messageFetcher = $messageFetcher;
        $this->entityManager = $entityManager;
        $this->boardRepository = $boardRepository;
    }

    /**
     * @Route("/thread/get/{id}", name="get_thread")
     * @param Thread $thread
     * @return Response
     */
    public function getThread(Thread $thread): Response
    {
        $messages = $this->messageFetcher->getMessages($thread->getId());

//        $message = new Message();
//        $message->setThread($thread);
//        $form = $this->createForm(MessageFormType::class, $message, [
//            'action' => $this->generateUrl('add_message')
//        ]);
//
        return $this->render('thread/getThread.html.twig', [
            'thread' => $thread,
            'messages' => $messages,
//            'message_form' => $form->createView()
        ]);
    }

    /**
     * @Route("/thread/add-thread/", name="add_thread")
     * @param Request $request
     * @return Response
     */
    public function createThread(Request $request): Response
    {
        $thread = new Thread();
        $form = $this->createForm(ThreadFormType::class, $thread);
        $board = $this->boardRepository->findOneBy(['id'=> $request->get('board-id')]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() && !is_null($board)) {
            $thread->setCreationTime(new \DateTimeImmutable());
            $thread->setBoard($board);
            //todo add author and picture
            $this->entityManager->persist($thread);
            $this->entityManager->flush();

            return $this->redirectToRoute('get_thread', ['id' => $thread->getId()]);
        } elseif (is_null($board)) {
            $form->get('text')->addError(new FormError('board is not found'));
        }

        return $this->render('thread/addThread.html.twig', [
            'thread_form' => $form->createView()
        ]);
    }
}

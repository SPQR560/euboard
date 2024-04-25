<?php

namespace App\Controller;

use App\Model\Board\Entity\Board;
use App\Model\Board\Repository\BoardRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Model\Thread\DbFetcher\IThreadFetcher;

class BoardController extends AbstractController
{
    private IThreadFetcher $threadFetcher;
    
    public function __construct(IThreadFetcher $treadFetcher)
    {
        $this->threadFetcher = $treadFetcher;
    }

    /**
     * @Route("/board/{path}", name="board_treads")
     */
    public function boardThreads(Board $board): Response
    {
        $threads = $this->threadFetcher->getThreads($board->getId());
        return $this->render('board/boardThreads.html.twig', [
            'threads' => $threads,
            'board' => $board
        ]);
    }
}

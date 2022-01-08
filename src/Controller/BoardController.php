<?php

namespace App\Controller;

use App\Model\Board\Entity\Board;
use App\Model\Board\Repository\BoardRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BoardController extends AbstractController
{
    private BoardRepository $boardRepository;
    public function __construct(BoardRepository $boardRepository)
    {
        $this->boardRepository = $boardRepository;
    }

    /**
     * @Route("/board/{path}", name="board_treads")
     * @param Board $board
     * @return Response
     */
    public function boardThreads(Board $board): Response
    {
        $threads = $board->getThreads();
        return $this->render('board/boardThreads.html.twig', [
            'threads' => $threads,
            'board' => $board
        ]);
    }
}

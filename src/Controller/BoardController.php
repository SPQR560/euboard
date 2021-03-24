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
     * @Route("/board_sidebar", name="board_sidebar")
     * @return Response
     */
    public function boardSidebar(): Response
    {
        $oneDay = 86400;
        $boards = $this->boardRepository->findBy([], ['name' => 'ASC']);
        return $this->render('board/sidebar.html.twig', [
            'boards' => $boards,
        ])->setSharedMaxAge($oneDay);
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

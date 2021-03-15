<?php

namespace App\Controller;

use App\Model\Board\Repository\BoardRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BoardController extends AbstractController
{
    private $boardRepository;
    public function __construct(BoardRepository $boardRepository)
    {
        $this->boardRepository = $boardRepository;
    }

    /**
     * @Route("/board_sidebar", name="board_sidebar")
     */
    public function boardSidebar(): Response
    {
        $boards = $this->boardRepository->findBy([], ['name' => 'ASC']);
        return $this->render('board/sidebar.html.twig', [
            'boards' => $boards,
        ])->setSharedMaxAge(86400);
    }
}

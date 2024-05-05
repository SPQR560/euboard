<?php

namespace App\Controller;

use App\AppLayers\Application\Fetcher\Board\IBoardFetcher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    private IBoardFetcher $boardFetcher;

    public function __construct(IBoardFetcher $boardFetcher)
    {
        $this->boardFetcher = $boardFetcher;
    }

    /**
     * @Route("/", name="main")
     */
    public function index(): Response
    {
        $boards = $this->boardFetcher->getBoards();

        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController', 'boards' => $boards,
        ]);
    }
}

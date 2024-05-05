<?php

namespace App\EventSubscriber;

use App\Model\Board\Repository\BoardRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Twig\Environment;

class TwigEventSubscriber implements EventSubscriberInterface
{
    private Environment $twig;

    private BoardRepository $boardRepository;

    public function __construct(Environment $twig, BoardRepository $boardRepository)
    {
        $this->twig = $twig;
        $this->boardRepository = $boardRepository;
    }

    public function onControllerEvent(ControllerEvent $event): void
    {
        $boards = $this->boardRepository->findBy([], ['name' => 'ASC']);
        $this->twig->addGlobal('sidebarBoards', $boards);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ControllerEvent::class => 'onControllerEvent',
        ];
    }
}

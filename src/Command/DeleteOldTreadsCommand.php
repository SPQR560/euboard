<?php

namespace App\Command;

use App\Model\Thread\Repository\ThreadRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class DeleteOldTreadsCommand extends Command
{
    protected static $defaultName = 'app:delete-old-treads';

    protected static $defaultDescription = 'command delete old treads';

    private ThreadRepository $threadRepository;

    public function __construct(ThreadRepository $threadRepository)
    {
        $this->threadRepository = $threadRepository;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription(self::$defaultDescription)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $this->threadRepository->deleteOldThreads();

        $io->success('Old threads have deleted');

        return Command::SUCCESS;
    }
}

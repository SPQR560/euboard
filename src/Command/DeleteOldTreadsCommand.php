<?php

namespace App\Command;

use App\AppLayers\Application\UseCase\Thread\RemoveOldThreadsUseCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class DeleteOldTreadsCommand extends Command
{
    protected static $defaultName = 'app:delete-old-treads';

    protected static string $defaultDescription = 'command delete old treads';

    private RemoveOldThreadsUseCase $removeOldThreadsUseCase;

    public function __construct(RemoveOldThreadsUseCase $removeOldThreadsUseCase)
    {
        $this->removeOldThreadsUseCase = $removeOldThreadsUseCase;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription(self::$defaultDescription);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $this->removeOldThreadsUseCase->removeOldThreads();

        $io->success('Old threads have deleted');

        return Command::SUCCESS;
    }
}

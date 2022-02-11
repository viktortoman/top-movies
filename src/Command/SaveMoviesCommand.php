<?php

namespace App\Command;

use App\Service\MovieApiService;
use Doctrine\DBAL\ConnectionException;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpFoundation\RequestStack;

class SaveMoviesCommand extends Command
{
    protected static $defaultName = 'save-movies';
    protected static $defaultDescription = 'Save movies into database by page.';

    private MovieApiService $movieApiService;

    /**
     * @param MovieApiService $movieApiService
     */
    public function __construct(
        MovieApiService $movieApiService
    ) {
        $this->movieApiService = $movieApiService;

        parent::__construct(self::$defaultName);
    }

    protected function configure(): void
    {
        $this->addArgument('page', InputArgument::OPTIONAL, 'Page for the API call.');
    }

    /**
     * @throws OptimisticLockException
     * @throws ConnectionException
     * @throws ORMException
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $page = $input->getArgument('page');

        if ($page) {
            $io->note(sprintf('You passed page argument: %s', $page));
        }

        $this->movieApiService->saveList($page);

        $io->success('Movie list save successfully.');

        return Command::SUCCESS;
    }
}

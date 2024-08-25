<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalogCommands\Commands;

use Danilocgsilva\EndpointsCatalogCommands\ConnectTrait;
use Danilocgsilva\EndpointsCatalogCommands\CommandTemplate;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Danilocgsilva\EndpointsCatalog\Repositories\PathRepository;

class ListPathsCommand extends CommandTemplate
{
    use ConnectTrait;

    protected function configure(): void
    {
        parent::configure();

        $this->setName('list-paths');
        $this->setDescription('List paths');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->connect();
        
        try {
            $pathRepository = new PathRepository($this->pdo);
            foreach ($pathRepository->list() as $path) {
                $pathString = $path->path;
                $output->writeln("* {$pathString}");
            }
            
            return 0;
        } catch (\Throwable $exception) {
            return $this->caughtException($exception, $output);
        }
    }
}

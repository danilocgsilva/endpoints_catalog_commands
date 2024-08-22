<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalogCommands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Danilocgsilva\EndpointsCatalog\Migrations;

final class MigrateCommand extends Command
{
    protected function configure(): void
    {
        parent::configure();

        $this->setName('migrate');
        $this->setDescription('Do a database migration.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            /** @var Migrations $migrations */
            $migrations = new Migrations();
            
            $output->writeln(
                $migrations->getOnSql()
            );
    
            return 0;
        } catch (\Throwable $exception) {
            $output->writeln(
                get_class($exception) . ": " .
                $exception->getMessage()
            );
            return 1;
        }
    }
}


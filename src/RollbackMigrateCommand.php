<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalogCommands;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

final class RollbackMigrateCommand extends MigrateTemplate
{
    protected function configure(): void
    {
        parent::configure();

        $this->setName('rollback-migrate');
        $this->setDescription('Do a rollback database migration.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $this->pdo->prepare($this->migrations->getRollbackSql())->execute();

            $output->writeln("Rollback done");
    
            return 0;
        } catch (Throwable $exception) {
            return $this->caughtException($exception, $output);
        }
    }
}


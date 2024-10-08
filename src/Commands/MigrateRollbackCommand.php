<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalogCommands\Commands;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Danilocgsilva\EndpointsCatalogCommands\{CommandTemplate, ConnectTrait};
use Throwable;

final class MigrateRollbackCommand extends CommandTemplate
{
    use ConnectTrait;
    
    protected function configure(): void
    {
        parent::configure();

        $this->setName('migrate:rollback');
        $this->setDescription('Do a rollback database migration.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->connectMRollback();
        
        try {
            $this->pdo->prepare($this->migrations->getRollbackString())->execute();

            $output->writeln("Rollback done");
    
            return 0;
        } catch (Throwable $exception) {
            return $this->caughtException($exception, $output);
        }
    }
}


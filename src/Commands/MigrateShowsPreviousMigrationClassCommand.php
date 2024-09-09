<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalogCommands\Commands;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Danilocgsilva\EndpointsCatalogCommands\{CommandTemplate, ConnectTrait};
use Throwable;

final class MigrateShowsPreviousMigrationClassCommand extends CommandTemplate
{
    use ConnectTrait;
    
    protected function configure(): void
    {
        parent::configure();

        $this->setName('migrate:shows-rollback-migration-class');
        $this->setDescription('Shows the next class to make a rollback.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->connectMRollback();
        
        try {
            $output->writeln(get_class($this->migrations));
    
            return 0;
        } catch (Throwable $exception) {
            return $this->caughtException($exception, $output);
        }
    }
}


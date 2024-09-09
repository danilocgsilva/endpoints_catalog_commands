<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalogCommands\Commands;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Danilocgsilva\EndpointsCatalogCommands\CommandTemplate;
use Danilocgsilva\EndpointsCatalogCommands\ConnectTrait;

final class MigrateShowsNextMigrationClassCommand extends CommandTemplate
{
    use ConnectTrait;

    protected function configure(): void
    {
        parent::configure();

        $this->setName('migrate:shows-next-migration-rollback');
        $this->setDescription('Shows the next migration class.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->connectMigrate();
        
        try {
            $output->writeln(get_class($this->migrations));
    
            return 0;
        } catch (\Throwable $exception) {
            return $this->caughtException($exception, $output);
        }
    }
}


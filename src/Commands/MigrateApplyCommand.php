<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalogCommands\Commands;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Danilocgsilva\EndpointsCatalogCommands\CommandTemplate;
use Danilocgsilva\EndpointsCatalogCommands\ConnectTrait;

final class MigrateApplyCommand extends CommandTemplate
{
    use ConnectTrait;

    protected function configure(): void
    {
        parent::configure();

        $this->setName('migrate:apply');
        $this->setDescription('Do a database migration.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->connectMigrate();
        
        try {
            $this->pdo->prepare($this->migrations->getString())->execute();
            
            $output->writeln("Migration applied!");
    
            return 0;
        } catch (\Throwable $exception) {
            return $this->caughtException($exception, $output);
        }
    }
}


<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalogCommands;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class MigrateCommand extends MigrateTemplate
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
            $this->pdo->prepare($this->migrations->getOnSql())->execute();
            
            $output->writeln("Migration applied!");
    
            return 0;
        } catch (\Throwable $exception) {
            return $this->caughtException($exception, $output);
        }
    }
}


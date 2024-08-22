<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalogCommands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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
        $output->writeln("Lets migrate!");

        return 0;
    }
}


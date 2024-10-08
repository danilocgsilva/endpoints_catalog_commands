<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalogCommands;

use Symfony\Component\Console\Command\Command;
use Throwable;
use Symfony\Component\Console\Output\OutputInterface;

class CommandTemplate extends Command
{
    use ConnectTrait;

    public function caughtException(Throwable $exception, OutputInterface $output): int
    {
        $output->writeln(
            get_class($exception) . ": " .
            $exception->getMessage()
        );
        return 1;
    }
}


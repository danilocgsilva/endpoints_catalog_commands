<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalogCommands;

use Symfony\Component\Console\Command\Command;
use Danilocgsilva\EndpointsCatalog\Migrations;
use Throwable;
use Symfony\Component\Console\Output\OutputInterface;
use PDO;

class MigrateTemplate extends Command
{
    protected Migrations $migrations;

    protected PDO $pdo;
    
    public function __construct(?string $name = null)
    {
        parent::__construct($name);
        $this->migrations = new Migrations();
        $this->pdo = new PDO(
            sprintf("mysql:host=%s;dbname=%s", getenv('DB_ENDPOINTSCATALOG_HOST'), getenv('DB_ENDPOINTSCATALOG_NAME')),
            getenv('DB_ENDPOINTSCATALOG_USER'),
            getenv('DB_ENDPOINTSCATALOG_PASSWORD')
        );
    }

    public function caughtException(Throwable $exception, OutputInterface $output): int
    {
        $output->writeln(
            get_class($exception) . ": " .
            $exception->getMessage()
        );
        return 1;
    }
}


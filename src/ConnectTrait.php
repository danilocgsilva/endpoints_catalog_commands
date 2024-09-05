<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalogCommands;

use Danilocgsilva\EndpointsCatalog\Migrations\MigrationInterface;
use Danilocgsilva\EndpointsCatalog\Migrations\Manager;
use PDO;

trait ConnectTrait
{
    protected MigrationInterface $migrations;

    protected PDO $pdo;

    private string $databaseName;

    protected function connectMigrate(): void
    {
        $this->connect();
        $migrationManager = new Manager($this->databaseName, $this->pdo);
        $nextMigrationName = $migrationManager->getNextMigration();
        
        $this->migrations = new $nextMigrationName;
    }

    protected function connectMRollback(): void
    {
        $this->connect();
        $migrationManager = new Manager($this->databaseName, $this->pdo);
        
        $this->migrations = $migrationManager->getPreviousMigration();
    }

    protected function connect(): void
    {
        $this->pdo = new PDO(
            sprintf("mysql:host=%s;dbname=%s", getenv('DB_ENDPOINTSCATALOG_HOST'), getenv('DB_ENDPOINTSCATALOG_NAME')),
            getenv('DB_ENDPOINTSCATALOG_USER'),
            getenv('DB_ENDPOINTSCATALOG_PASSWORD')
        );

        $this->databaseName = getenv('DB_ENDPOINTSCATALOG_NAME');
    }
}

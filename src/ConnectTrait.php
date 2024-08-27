<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalogCommands;

use Danilocgsilva\EndpointsCatalog\Migrations\MigrationInterface;
use Danilocgsilva\EndpointsCatalog\Migrations\MigrationManager;
use PDO;

trait ConnectTrait
{
    protected MigrationInterface $migrations;

    protected PDO $pdo;

    protected function connect()
    {
        $migrationManager = new MigrationManager();

        $nextMigrationName = $migrationManager->getNextMigrationClass();
        
        $this->migrations = new $nextMigrationName;
        $this->pdo = new PDO(
            sprintf("mysql:host=%s;dbname=%s", getenv('DB_ENDPOINTSCATALOG_HOST'), getenv('DB_ENDPOINTSCATALOG_NAME')),
            getenv('DB_ENDPOINTSCATALOG_USER'),
            getenv('DB_ENDPOINTSCATALOG_PASSWORD')
        );
    }
}

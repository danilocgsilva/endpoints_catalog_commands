<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalogCommands;

use Danilocgsilva\EndpointsCatalog\Migrations;
use PDO;

trait ConnectTrait
{
    protected Migrations $migrations;

    protected PDO $pdo;

    private function connect()
    {
        $this->migrations = new Migrations();
        $this->pdo = new PDO(
            sprintf("mysql:host=%s;dbname=%s", getenv('DB_ENDPOINTSCATALOG_HOST'), getenv('DB_ENDPOINTSCATALOG_NAME')),
            getenv('DB_ENDPOINTSCATALOG_USER'),
            getenv('DB_ENDPOINTSCATALOG_PASSWORD')
        );
    }
}

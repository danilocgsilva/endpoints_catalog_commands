<?php

declare(strict_types=1);

use Danilocgsilva\EndpointsCatalogCommands\Commands\MigrateCommand;
use Danilocgsilva\EndpointsCatalogCommands\Commands\RollbackMigrateCommand;
use Danilocgsilva\EndpointsCatalogCommands\Commands\InsertEndpoint;
use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArgvInput;

require_once __DIR__ . '/vendor/autoload.php';

$env = (new ArgvInput())->getParameterOption(['--env', '-e'], 'dev');

if ($env) {
    $_ENV['APP_ENV'] = $env;
}

/** @var ContainerInterface $container */
$container = (new ContainerBuilder())
    ->build();

try {
    /** @var Application $application */
    $application = $container->get(Application::class);

    $application->add($container->get(MigrateCommand::class));
    $application->add($container->get(RollbackMigrateCommand::class));
    $application->add($container->get(InsertEndpoint::class));

    exit($application->run());
} catch (Throwable $exception) {
    echo $exception->getMessage();
    echo $exception->getTraceAsString();
    exit(1);
}
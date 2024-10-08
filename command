<?php

declare(strict_types=1);

use Danilocgsilva\EndpointsCatalogCommands\Commands\{
    MigrateApplyCommand, MigrateRollbackCommand, SetEndpointCommand,
    ListPathsCommand, DnsListCommand, AddDnsCommand,
    AddPathCommand, ListEndpointsCommand, DnsAddDescriptionCommand,
    RenamePathCommand, RemoveEndpointCommand, 
};
use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArgvInput;

require_once __DIR__ . '/vendor/autoload.php';

$env = (new ArgvInput())->getParameterOption(['--env', '-e'], 'dev');

if ($env) {
    $_ENV['APP_ENV'] = $env;
}

class AddCommand
{
    public function __construct(private $application, private $container) {}
    
    public function add(string $commandName): self
    {
        $this->application->add($this->container->get($commandName));
        return $this;
    }
}

/** @var ContainerInterface $container */
$container = (new ContainerBuilder())
    ->build();

try {
    /** @var Application $application */
    $application = $container->get(Application::class);

    (new AddCommand($application, $container))
        ->add(MigrateApplyCommand::class)
        ->add(MigrateRollbackCommand::class)
        ->add(ListPathsCommand::class)
        ->add(AddDnsCommand::class)
        ->add(DnsAddDescriptionCommand::class)
        ->add(DnsListCommand::class)
        ->add(AddPathCommand::class)
        ->add(SetEndpointCommand::class)
        ->add(ListEndpointsCommand::class)
        ->add(RenamePathCommand::class)
        ->add(RemoveEndpointCommand::class)
    ;

    exit($application->run());
} catch (Throwable $exception) {
    echo $exception->getMessage();
    echo $exception->getTraceAsString();
    exit(1);
}
<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalogCommands\Commands;

use Danilocgsilva\EndpointsCatalog\Models\Path;
use Danilocgsilva\EndpointsCatalog\Repositories\PathRepository;
use Danilocgsilva\EndpointsCatalogCommands\AskTrait;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Danilocgsilva\EndpointsCatalogCommands\CommandTemplate;
use Danilocgsilva\EndpointsCatalogCommands\ConnectTrait;


final class InsertEndpointCommand extends CommandTemplate
{
    use ConnectTrait;
    use AskTrait;

    private InputInterface $input;

    private OutputInterface $output;

    protected function configure(): void
    {
        parent::configure();

        $this->setName('insert-endpoint');
        $this->setDescription('Saves an endpoint.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->connect();
        $this->fillInputOutput($input, $output);
        
        try {
            (new PathRepository($this->pdo))->save(
                (new Path())->setPath(
                    ($pathString = $this->getAskAnswer('Write the path'))
                )
            );
            
            $output->writeln("You saved {$pathString}.");
    
            return 0;
        } catch (\Throwable $exception) {
            return $this->caughtException($exception, $output);
        }
    }

    private function fillInputOutput(InputInterface $input, OutputInterface $output): void
    {
        $this->input = $input;
        $this->output = $output;
    }
}


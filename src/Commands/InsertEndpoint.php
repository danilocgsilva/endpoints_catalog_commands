<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalogCommands\Commands;

use Danilocgsilva\EndpointsCatalog\Models\Path;
use Danilocgsilva\EndpointsCatalog\Repositories\PathRepository;
use Danilocgsilva\EndpointsCatalogCommands\AskTrait;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Danilocgsilva\EndpointsCatalogCommands\MigrateTemplate;
use Danilocgsilva\EndpointsCatalogCommands\ConnectTrait;


final class InsertEndpoint extends MigrateTemplate
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
            $pathString = $this->getAskAnswer('Write the path');

            $path = (new Path())->setPath($pathString);
            (new PathRepository($this->pdo))->save($path);
            
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


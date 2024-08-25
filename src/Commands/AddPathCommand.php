<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalogCommands\Commands;

use Danilocgsilva\EndpointsCatalog\Repositories\PathRepository;
use Danilocgsilva\EndpointsCatalogCommands\{
    AskTrait, ConnectTrait, CommandTemplate
};
use Danilocgsilva\EndpointsCatalog\Models\Path;
use Symfony\Component\Console\{
    Input\InputInterface, 
    Output\OutputInterface
};

class AddPathCommand extends CommandTemplate
{
    use ConnectTrait;
    use AskTrait;

    private InputInterface $input;

    private OutputInterface $output;

    protected function configure(): void
    {
        parent::configure();

        $this->setName('add-path');
        $this->setDescription('Register a path.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->connect();
        $this->fillInputOutput($input, $output);
        
        try {
            $pathRepository = new PathRepository($this->pdo);
            $path = new Path();
            $pathString = $this->getAskAnswer('Write the path');
            $path->setPath($pathString);
            $pathRepository->save($path);
            
            return 0;
        } catch (\Throwable $exception) {
            return $this->caughtException($exception, $output);
        }
    }
}

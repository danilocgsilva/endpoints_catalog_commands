<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalogCommands\Commands;

use Danilocgsilva\EndpointsCatalogCommands\{
    AskTrait,
    ConnectTrait,
    CommandTemplate
};
use Symfony\Component\Console\{
    Input\InputInterface,
    Output\OutputInterface,
    Question\ChoiceQuestion
};
use Danilocgsilva\EndpointsCatalog\Repositories\PathRepository;
use Danilocgsilva\EndpointsCatalog\Models\Path;


class RenamePathCommand extends CommandTemplate
{
    // use ConnectTrait;
    use AskTrait;

    private InputInterface $input;

    private OutputInterface $output;

    // private PathRepository $pathRepository;

    protected function configure(): void
    {
        parent::configure();

        $this->setName('rename-path');
        $this->setDescription('Rename a path');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->connect();

        /** @var PathRepository $pathRepository */
        $pathRepository = new PathRepository($this->pdo);

        $this->fillInputOutput($input, $output);

        try {
            $pathObjectChoosed = $this->getChoosedPathObjectFromUserSelection($pathRepository);

            $newPathNameFromUser = $this->getAskAnswer('Write the path');

            $pathRepository->replace(
                $pathObjectChoosed->id,
                (new Path())->setPath($newPathNameFromUser)
            );

            return 0;
        } catch (\Throwable $exception) {
            return $this->caughtException($exception, $output);
        }
    }

    private function getChoosedPathObjectFromUserSelection(PathRepository $pathRepository)
    {
        $helper = $this->getHelper('question');

        $pathsObjectsList = $pathRepository->list();
        $choices = array_map(fn($entry) => $entry->path, $pathsObjectsList);
        $questionPaths = new ChoiceQuestion(
            'Select the path to change',
            $choices
        );
        $questionPaths->setMultiselect(false);

        $pathChoiceFromUser = $helper->ask($this->input, $this->output, $questionPaths);

        return array_values(array_filter($pathsObjectsList, fn($entry) => $entry->path === $pathChoiceFromUser))[0];
    }
}

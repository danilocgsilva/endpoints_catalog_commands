<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalogCommands;

use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

trait AskTrait
{
    public function getAskAnswer(string $questionString): string
    {
        $question = new Question("{$questionString}: ");
        $helper = $this->getHelper('question');
        return $helper->ask($this->input, $this->output, $question);
    }

    protected function fillInputOutput(InputInterface $input, OutputInterface $output): void
    {
        $this->input = $input;
        $this->output = $output;
    }
}

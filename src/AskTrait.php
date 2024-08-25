<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalogCommands;

use Symfony\Component\Console\Question\Question;

trait AskTrait
{
    public function getAskAnswer(string $questionString): string
    {
        $question = new Question("{$questionString}: ");
        $helper = $this->getHelper('question');
        return $helper->ask($this->input, $this->output, $question);
    }
}

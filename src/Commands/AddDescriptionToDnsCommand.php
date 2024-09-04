<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalogCommands\Commands;

use Danilocgsilva\EndpointsCatalogCommands\{
    AskTrait, ConnectTrait, CommandTemplate
};
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Danilocgsilva\EndpointsCatalog\Repositories\DnsRepository;
use Danilocgsilva\EndpointsCatalog\Models\Dns;

class AddDescriptionToDnsCommand extends CommandTemplate
{
    use ConnectTrait;
    use AskTrait;
    
    protected function configure(): void
    {
        parent::configure();

        $this->setName('add-description-dns');
        $this->setDescription('Add a description to the dns.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->connect();
        $this->fillInputOutput($input, $output);
        
        $helper = $this->getHelper('question');

        $dnsRepository = new DnsRepository($this->pdo);

        /** @var array<Dns> */
        $optionsObjs = $dnsRepository->list();

        $optionsString = array_map(fn ($entry) => $entry->dns, $optionsObjs);

        $choice = $helper->ask($input, $output, new ChoiceQuestion(
            'Selection the DNS to set a description',
            $optionsString
        ));

        /** @var Dns $objectChoosed */
        $objectChoosed = array_values(array_filter($optionsObjs, fn ($entry) => $entry->dns === $choice))[0];

        $descriptionFromUser = $this->getAskAnswer('Write a description');

        $updatedDns = (new Dns())
            ->setDns($objectChoosed->dns)
            ->setDescription($descriptionFromUser);

        if (isset($objectChoosed->port)) {
            $updatedDns->setPort($objectChoosed->port);
        }

        $dnsRepository->replace($objectChoosed->id, $updatedDns);

        return 0;
    }
}

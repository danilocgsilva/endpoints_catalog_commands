<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalogCommands\Commands;

use Danilocgsilva\EndpointsCatalog\Repositories\DnsPathRepository;
use Danilocgsilva\EndpointsCatalogCommands\ConnectTrait;
use Danilocgsilva\EndpointsCatalogCommands\CommandTemplate;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Danilocgsilva\EndpointsCatalog\Repositories\PathRepository;
use Danilocgsilva\EndpointsCatalog\Repositories\DnsRepository;
use Danilocgsilva\EndpointsCatalog\Services\EndpointService;
use Symfony\Component\Console\Question\ChoiceQuestion;

class RemoveEndpointCommand extends CommandTemplate
{
    use ConnectTrait;

    protected function configure(): void
    {
        parent::configure();

        $this->setName('remove-endpoint');
        $this->setDescription('Remove an endpoint');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->connect();
        
        try {
            /** @var DnsPathRepository $dnsPathRepository */
            $dnsPathRepository = new DnsPathRepository($this->pdo);
            $endpoints = $this->getEndpointsList($dnsPathRepository);
            $endpointsStrings = [];
            foreach ($endpoints as $key => $endpoint) {
                $endpointsStrings[$key] = $endpoint->getEndpointString();
            }

            $questionEndpoint = new ChoiceQuestion(
                'Select the endpoint to remove',
                $endpointsStrings
            );
            $questionEndpoint->setMultiselect(false);
            $helper = $this->getHelper('question');
            $questionEndpointAnswered = $helper->ask($input, $output, $questionEndpoint);

            /** @var EndpointService $questionEndpointAnsweredObject */
            $questionEndpointAnsweredObject = array_values(array_filter(
                $endpoints, 
                fn ($entry) => $entry->getEndpointString() === $questionEndpointAnswered
            ))[0];

            $dnsPathToRemove = $dnsPathRepository->findByDnsIdAndPathId(
                $questionEndpointAnsweredObject->dns->id,
                $questionEndpointAnsweredObject->path->id
            );

            $dnsPathRepository->delete($dnsPathToRemove->id);

            return 0;
        } catch (\Throwable $exception) {
            return $this->caughtException($exception, $output);
        }
    }

    /**
     * @param \Danilocgsilva\EndpointsCatalog\Repositories\DnsPathRepository $dnsPathRepository
     * @return array<EndpointService>
     */
    private function getEndpointsList(DnsPathRepository $dnsPathRepository): array 
    {
        // $dnsPathRepository = new DnsPathRepository($this->pdo);
        $pathRepository = new PathRepository($this->pdo);
        $dnsRepository = new DnsRepository($this->pdo);

        $endpointsList = array_map(
            fn ($entry) => new EndpointService(
                $dnsRepository->get($entry->dns_id),
                $pathRepository->get($entry->path_id)
            ),
            $dnsPathRepository->list()
        );

        usort(
            $endpointsList, 
            fn ($first, $second) => strcmp($first->getEndpointString(), $second->getEndpointString())
        );

        return $endpointsList;
    }
}

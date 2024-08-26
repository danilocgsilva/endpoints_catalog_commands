<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalogCommands\Commands;

use Danilocgsilva\EndpointsCatalog\Repositories\DnsPathRepository;
use Danilocgsilva\EndpointsCatalog\Repositories\PathRepository;
use Danilocgsilva\EndpointsCatalog\Services\EndpointService;
use Danilocgsilva\EndpointsCatalogCommands\{
    ConnectTrait, CommandTemplate
};
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Danilocgsilva\EndpointsCatalog\Repositories\DnsRepository;

class ListEndpointsCommand extends CommandTemplate
{
    use ConnectTrait;

    protected function configure(): void
    {
        parent::configure();

        $this->setName('list-endpoints');
        $this->setDescription('List endpoints');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->connect();
        
        try {
            $dnsPathRepository = new DnsPathRepository($this->pdo);
            $pathRepository = new PathRepository($this->pdo);
            $dnsRepository = new DnsRepository($this->pdo);
            foreach ($dnsPathRepository->list() as $dnsPathEntry) {
                $endpointString = (new EndpointService(
                    $dnsRepository->get($dnsPathEntry->dns_id),
                    $pathRepository->get($dnsPathEntry->path_id)
                ))->getEndpointString();
                $output->writeln(" * " . $endpointString);
            }
            return 0;
        } catch (\Throwable $exception) {
            return $this->caughtException($exception, $output);
        }
    }
}

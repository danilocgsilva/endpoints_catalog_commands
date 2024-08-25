<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalogCommands\Commands;

use Danilocgsilva\EndpointsCatalogCommands\ConnectTrait;
use Danilocgsilva\EndpointsCatalogCommands\CommandTemplate;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Danilocgsilva\EndpointsCatalog\Repositories\DnsRepository;

class ListDnsCommand extends CommandTemplate
{
    use ConnectTrait;

    protected function configure(): void
    {
        parent::configure();

        $this->setName('list-dns');
        $this->setDescription('List dns');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->connect();
        
        try {
            $dnsRepository = new DnsRepository($this->pdo);
            foreach ($dnsRepository->list() as $dns) {
                $dnsString = $dns->dns;
                $output->writeln("* {$dnsString}");
            }
            
            return 0;
        } catch (\Throwable $exception) {
            return $this->caughtException($exception, $output);
        }
    }
}

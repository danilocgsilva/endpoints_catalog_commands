<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalogCommands\Commands;

use Danilocgsilva\EndpointsCatalogCommands\ConnectTrait;
use Danilocgsilva\EndpointsCatalogCommands\CommandTemplate;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Danilocgsilva\EndpointsCatalog\Repositories\DnsRepository;

class DnsListCommand extends CommandTemplate
{
    use ConnectTrait;

    protected function configure(): void
    {
        parent::configure();

        $this->setName('dns:list');
        $this->setDescription('List dns');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->connect();
        
        try {
            $dnsRepository = new DnsRepository($this->pdo);
            foreach ($dnsRepository->list() as $dns) {
                $dnsString = $dns->dns;
                $stringToWrite = "* {$dnsString}";
                if (isset($dns->description)) {
                    $stringToWrite .= " - {$dns->description}";
                }
                $output->writeln($stringToWrite);
            }
            
            return 0;
        } catch (\Throwable $exception) {
            return $this->caughtException($exception, $output);
        }
    }
}

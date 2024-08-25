<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalogCommands\Commands;

use Danilocgsilva\EndpointsCatalog\Models\Dns;
use Danilocgsilva\EndpointsCatalogCommands\AskTrait;
use Danilocgsilva\EndpointsCatalogCommands\ConnectTrait;
use Danilocgsilva\EndpointsCatalogCommands\CommandTemplate;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Danilocgsilva\EndpointsCatalog\Repositories\DnsRepository;

class AddDnsCommand extends CommandTemplate
{
    use ConnectTrait;
    use AskTrait;

    private InputInterface $input;

    private OutputInterface $output;

    protected function configure(): void
    {
        parent::configure();

        $this->setName('add-dns');
        $this->setDescription('Register a dns.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->connect();
        $this->fillInputOutput($input, $output);
        
        try {
            $dnsRepository = new DnsRepository($this->pdo);
            $dns = new Dns();
            $dnsString = $this->getAskAnswer('Write the dns');
            $dns->setDns($dnsString);
            $dnsRepository->save($dns);
            
            return 0;
        } catch (\Throwable $exception) {
            return $this->caughtException($exception, $output);
        }
    }
}

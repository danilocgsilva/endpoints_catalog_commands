<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalogCommands\Commands;

use Danilocgsilva\EndpointsCatalog\Repositories\DnsPathRepository;
use Danilocgsilva\EndpointsCatalog\Repositories\PathRepository;
use Danilocgsilva\EndpointsCatalogCommands\{CommandTemplate, ConnectTrait};
use Symfony\Component\Console\{
    Input\InputInterface, 
    Output\OutputInterface,
    Question\ChoiceQuestion
};
use Danilocgsilva\EndpointsCatalog\Models\{Dns, Path};
use Danilocgsilva\EndpointsCatalog\Repositories\DnsRepository;

class SetEndpointCommand extends CommandTemplate
{
    protected function configure(): void
    {
        parent::configure();

        $this->setName('set-endpoint');
        $this->setDescription('Set an endpoint.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->connect();

        try {
            $helper = $this->getHelper('question');
            
            $dnssOptions = $this->getDnssStrings();

            $questionDns = new ChoiceQuestion(
                'Select the dns to compose the endpoint.',
                $dnssOptions
            );
            $questionDns->setMultiselect(false);

            $dnsChoice = $helper->ask($input, $output, $questionDns);

            $paths = $this->getPaths();
            $pathsOptions = $this->getPathsString($paths);

            $questionPath = new ChoiceQuestion(
                'Select the path to compose the endpoint.',
                $pathsOptions
            );
            $questionPath->setMultiselect(false);

            $pathChoice = $helper->ask($input, $output, $questionPath);

            $output->writeln('You selected as dns: ' . $dnsChoice);
            $output->writeln('You selected as path: ' . $pathChoice);

            $choosenDnsObject = $this->pickDns($dnsChoice, $dnss);
            $choosenPathObject = $this->pickPath($pathChoice, $paths);

            $dnsPathRepository = new DnsPathRepository($this->pdo);
            $dnsPathRepository->saveEndpoint($choosenDnsObject, $choosenPathObject);
            
            return 0;
        } catch (\Throwable $exception) {
            return $this->caughtException($exception, $output);
        }
    }

    /**
     * @return array<Dns>
     */
    private function getDnss(): array
    {
        $dnsRepository = new DnsRepository($this->pdo);
        return $dnsRepository->list();
    }

    /**
     * @param array<Dns> $dnss
     * @return array<string>
     */
    private function getDnssStrings(): array
    {
        return array_map(fn ($entry) => $entry->dns, $this->getDnss());
    }

    /**
     * @return array<Path>
     */
    private function getPaths(): array
    {
        $pathRepository = new PathRepository($this->pdo);
        return $pathRepository->list();
    }

    /**
     * @param array<Path> $paths
     * @return array<string>
     */
    private function getPathsString(array $paths): array
    {
        return array_map(fn ($entry) => $entry->path, $paths);
    }

    /**
     * @param string $dnsChoice
     * @param array<Dns> $dnsList
     * @return Dns
     */
    private function pickDns(string $dnsChoice, array $dnsList): Dns
    {
        return array_values(
            array_filter($dnsList, fn ($entry) => $entry->dns === $dnsChoice)
        )[0];
    }

    /**
     * @param string $pathChoise
     * @param array<Path> $pathList
     * @return Path
     */
    private function pickPath(string $pathChoice, array $pathList): Path
    {
        return array_values(
            array_filter($pathList, fn ($entry) => $entry->path === $pathChoice)
        )[0];
    }
}

<?php

namespace App\Command;

use App\Formatter\ChartFormatter;
use App\Formatter\TextFormatter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpClient\CurlHttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiMonitorCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'api:monitor';

    /**
     * @var TextFormatter
     */
    private $formatter;

    protected function configure(): void
    {
        $this
            ->setDescription('Monitor API performance in real time')
            ->addArgument('url', InputArgument::REQUIRED, 'URL to monitor')
            ->addOption('chart', null, InputOption::VALUE_NONE, "Display response time as a chart")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (false === $input->getOption('chart')) {
            $formatterClass = TextFormatter::class;
        } else {
            $formatterClass = ChartFormatter::class;
        }

        $this->formatter = new $formatterClass($input, $output, "API Monitoring");

        $url = $input->getArgument('url');

        if (false === \is_string($url)) {
            throw new \RuntimeException("url is required");
        }

        $client = new CurlHttpClient();

        while (true) {
            $time = $this->doRequest($client, $url);

            $this->formatter->display($time);
        }

        return 0;
    }

    protected function doRequest(HttpClientInterface $client, string $url): float
    {
        $response = $client->request('GET', $url);

        if (200 !== $response->getStatusCode()) {
            throw new \Exception('Got Status Code ' . $response->getStatusCode());
        }

        return $response->getInfo('starttransfer_time') - $response->getInfo('connect_time');
    }
}

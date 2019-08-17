<?php

namespace App\Service\Utils;

use App\Service\QuotesService\QuotesStorageInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class QuandleApiClient implements QuotesStorageInterface
{
    private const API_URL = 'https://www.quandl.com/api/v3/datasets/WIKI/%s.csv';

    private $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function getQuotes(string $companySymbol, \DateTime $startDate, \DateTime $endDate): array
    {
        $url = sprintf(static::API_URL, $companySymbol);
        $response = $this->httpClient->request(Request::METHOD_GET, $url, ['query' => [
            'order' => 'asc',
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d')
        ]]);
        return $this->parseRawData($response->getContent());
    }

    private function parseRawData(string $data): array
    {
        $result = [];
        $lines = explode("\n", $data);
        array_pop($lines);      // the last line is always empty
        $header = str_getcsv(array_shift($lines));
        foreach ($lines as $line) {
            $result[] = array_combine($header, str_getcsv($line));
        }
        return $result;
    }
}
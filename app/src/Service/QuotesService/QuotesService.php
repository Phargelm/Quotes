<?php

namespace App\Service\QuotesService;

use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class QuotesService
{
    private $companiesList;
    private $quotesStorage;
    private $eventDispatcher;

    public function __construct(
        string $companiesFilename,
        QuotesStorageInterface $quotesStorage,
        EventDispatcherInterface $eventDispatcher)
    {
        $this->companiesList = $this->parseCompaniesData($companiesFilename);
        $this->quotesStorage = $quotesStorage;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function getQuotes(string $companySymbol, \DateTime $startDate, \DateTime $endDate, string $email): array
    {
        $companyName = $this->companiesList[$companySymbol][1];
        $event = new QuotesRequestedEvent($companyName, $startDate, $endDate, $email);
        $this->eventDispatcher->dispatch($event, QuotesRequestedEvent::NAME);
        return $this->quotesStorage->getQuotes($companySymbol, $startDate, $endDate);
    }
    
    public function getCompaniesList(): array
    {
        return $this->companiesList;
    }

    private function parseCompaniesData($filename): array
    {
        if(!file_exists($filename) || !is_readable($filename)) {
            throw new \Exception("File $filename does not exists or is not readable.");
        }

        $result = [];
        if (($resource = fopen($filename, 'r')) !== false) {
            while (($row = fgetcsv($resource)) !== false ) {
                $result[$row[0]] = $row;
            }
            fclose($resource);
        }
        
        return $result;
    }
}
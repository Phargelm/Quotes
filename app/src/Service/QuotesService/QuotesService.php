<?php

namespace App\Service\QuotesService;

use App\Entity\Company;
use App\Repository\CompanyRepository;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class QuotesService
{
    private $companyRepository;
    private $quotesStorage;
    private $eventDispatcher;
    
    public function __construct(
        QuotesStorageInterface $quotesStorage,
        CompanyRepository $companyRepository,
        EventDispatcherInterface $eventDispatcher)
    {
        $this->companyRepository = $companyRepository;
        $this->quotesStorage = $quotesStorage;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function getQuotes(string $companySymbol, \DateTime $startDate, \DateTime $endDate, string $email): array
    {
        $company = $this->companyRepository->findBySymbol($companySymbol);
        $event = new QuotesRequestedEvent($company->getName(), $startDate, $endDate, $email);
        $this->eventDispatcher->dispatch($event, QuotesRequestedEvent::NAME);
        return $this->quotesStorage->getQuotes($companySymbol, $startDate, $endDate);
    }
    
    public function getCompany(string $symbol): ?Company
    {
        return $this->companyRepository->findBySymbol($symbol);
    }
}
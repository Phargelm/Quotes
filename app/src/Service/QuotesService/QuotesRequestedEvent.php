<?php

namespace App\Service\QuotesService;

class QuotesRequestedEvent
{
    public const NAME = 'quotes.requested';

    private $companyName;
    private $startDate;
    private $endDate;
    private $email;

    public function __construct(string $companyName, \DateTime $startDate, \DateTime $endDate, string $email)
    {
        $this->companyName = $companyName;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->email = $email;
    }

    public function getCompanyName(): string
    {
        return $this->companyName;
    }

    public function getStartDate(): \DateTime
    {
        return $this->startDate;
    }

    public function getEndDate(): \DateTime
    {
        return $this->endDate;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
<?php

namespace App\Model;

class GetQuotesDTO
{
    private $companySymbol;
    private $startDate;
    private $endDate;
    private $email;
    
    public function setCompanySymbol(?string $symbol)
    {
        $this->companySymbol = trim($symbol);
    }

    public function getCompanySymbol(): ?string
    {
        return $this->companySymbol;
    }

    public function setStartDate(?string $startDate)
    {
        $this->startDate = trim($startDate);
    }

    public function getStartDate(): ?string
    {
        return $this->startDate;
    }

    public function setEndDate(?string $endDate)
    {
        $this->endDate = trim($endDate);
    }

    public function getEndDate(): ?string
    {
        return $this->endDate;
    }

    public function setEmail(?string $email)
    {
        $this->email = trim($email);
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }
}
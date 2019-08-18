<?php

namespace App\Entity;

class Company
{
    private $id;
    private $symbol;
    private $name;
    private $ipoYear;
    private $sector;
    private $industry;
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id)
    {
        $this->id = $id;
        return $this;
    }

    public function getSymbol(): string
    {
        return $this->symbol;
    }

    public function setSymbol(string $symbol)
    {
        $this->symbol = $symbol;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;
        return $this;
    }

    public function getIpoYear(): ?string
    {
        return $this->ipoYear;
    }

    public function setIpoYear(?string $ipoYear)
    {
        $this->ipoYear = $ipoYear;
        return $this;
    }

    public function getSector(): ?string
    {
        return $this->sector;
    }

    public function setSector(?string $sector)
    {
        $this->sector = $sector;
        return $this;
    }

    public function getIndustry(): ?string
    {
        return $this->industry;
    }

    public function setIndustry(?string $industry)
    {
        $this->industry = $industry;
        return $this;
    }
}
<?php

namespace App\Repository;

use App\Entity\Company;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class CompanyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Company::class);
    }

    public function getAllSymbols(): array
    {
        $result = $this->createQueryBuilder('c')->getQuery()->getArrayResult();
        return array_column($result, 'symbol', 'symbol');
    }

    public function findBySymbol(string $symbol): ?Company
    {
        $companies = $this->findBy(['symbol' => $symbol], null, 1);
        return empty($companies) ? null : $companies[0];
    }
}
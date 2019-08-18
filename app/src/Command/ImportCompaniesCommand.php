<?php

namespace App\Command;

use App\Entity\Company;
use App\Repository\CompanyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ImportCompaniesCommand extends Command
{
    private const NASDAQ_URL = 'http://www.nasdaq.com/screening/companies-by-name.aspx?&render=download';
    
    protected static $defaultName = 'app:import-companies';

    private $httpClient;
    private $companyRepository;
    private $entityManager;

    public function __construct(
        HttpClientInterface $httpClient,
        CompanyRepository $companyRepository,
        EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->httpClient = $httpClient;
        $this->companyRepository = $companyRepository;
        $this->entityManager = $entityManager;
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        /**
         * We need to populate database with a fresh companies data retrieved from nasdaq. Note, that we can't
         * just truncate all companies data in table. Despite there only one table and no foreign keys (FK) now,
         * truncation can cause problems with adding FK to the tables related to `companies` table in the future.
         * Therefore, we will just insert new rows.
         */

        $response = $this->httpClient->request(Request::METHOD_GET, static::NASDAQ_URL);

        if ($response->getStatusCode() !== Response::HTTP_OK) {
            $output->writeln('Error is occurred during data retrieving.');
            return;
        }

        $lines = explode("\n", $response->getContent());
        array_shift($lines);    // remove headers
        $newCompaniesCounter = 0;
        $existingSymbols = $this->companyRepository->getAllSymbols();

        foreach ($lines as $line) {

            $companyData = str_getcsv($line);
            $companySymbol = trim($companyData[0]);

            // continue if empty string has is parsed, or symbol already exists
            if (!$companySymbol || isset($existingSymbols[$companySymbol])) {
                continue;
            }

            // to prevent duplication in csv data
            $existingSymbols[$companySymbol] = $companySymbol;

            $ipoYear = trim($companyData[4]);
            $sector = trim($companyData[5]);
            $industry = trim($companyData[6]);

            $company = (new Company())
                ->setSymbol($companySymbol)
                ->setName(trim($companyData[1]))
                ->setIpoYear($ipoYear == 'n/a' ? null : $ipoYear)
                ->setSector($sector == 'n/a' ? null : $sector)
                ->setIndustry($industry == 'n/a' ? null : $industry);

            $this->entityManager->persist($company);
            $newCompaniesCounter ++;
        }

        $this->entityManager->flush();
        $output->writeln($newCompaniesCounter . ' new companies are added successfully!');
    }
}
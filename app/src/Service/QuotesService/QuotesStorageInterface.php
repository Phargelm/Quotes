<?php

namespace App\Service\QuotesService;

interface QuotesStorageInterface {

    function getQuotes(string $companySymbol, \DateTime $startDate, \DateTime $endDate): array;
}
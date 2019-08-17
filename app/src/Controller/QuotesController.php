<?php

namespace App\Controller;

use App\Model\GetQuotesDTO;
use App\Service\QuotesService\QuotesService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class QuotesController extends AbstractController
{
    public function index(): Response
    {
        return $this->render('base.html.twig');
    }

    /**
     * @ParamConverter("getQuotesDTO", class=GetQuotesDTO::class, options={"type" = "DTO"})
     */
    public function getQuotes(?GetQuotesDTO $getQuotesDTO, QuotesService $quotesService): Response
    {
        $quotes = $quotesService->getQuotes(
            $getQuotesDTO->getCompanySymbol(),
            new \DateTime($getQuotesDTO->getStartDate()),
            new \DateTime($getQuotesDTO->getEndDate()),
            $getQuotesDTO->getEmail()
        );

        return new JsonResponse($quotes);
    }

    public function getCompany($symbol, QuotesService $quotesService)
    {
        $companiesList = $quotesService->getCompaniesList();
        $company = $companiesList[$symbol] ?? null;
        return new JsonResponse($company, $company ? Response::HTTP_OK : Response::HTTP_NOT_FOUND);
    }
}

<?php

namespace App\Controller;

use App\Model\GetQuotesDTO;
use App\Service\QuotesService\QuotesService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class QuotesController extends AbstractController
{
    public function index(): Response
    {
        return $this->render('index.html');
    }

    /**
     * @ParamConverter("getQuotesDTO", class=GetQuotesDTO::class, options={"type" = "DTO"})
     * DTO validation occurs in App\Core\RequestToDTOConverter class, thus in controller it is always valid.
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

    /**
     * This action is used on front-end side for company symbol validation purposes.
     * If the requested company exists, a response will be returned with the company data, otherwise error 404.
     * Although subsequently the company data is not used on the client side, this action serves for general purposes.
     */
    public function getCompany($symbol, QuotesService $quotesService, SerializerInterface $serializer)
    {
        $company = $quotesService->getCompany($symbol);

        if ($company) {
            $responseContent = $serializer->serialize($company, 'json');
            return new JsonResponse($responseContent, Response::HTTP_OK, [], true);
        }

        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }
}

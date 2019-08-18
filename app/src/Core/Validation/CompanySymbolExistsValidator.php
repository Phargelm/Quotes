<?php

namespace App\Core\Validation;

use App\Service\QuotesService\QuotesService;
use Symfony\Component\HttpFoundation\File\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CompanySymbolExistsValidator extends ConstraintValidator
{
    private $quotesService;

    public function __construct(QuotesService $quotesService)
    {
        $this->quotesService = $quotesService;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof CompanySymbolExists) {
            throw new UnexpectedTypeException($constraint, ContainsAlphanumeric::class);
        }

        if ($value === null || $value === '') {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }
        
        if (!$this->quotesService->getCompany($value)) {
            $this->context->buildViolation($constraint->message)
            ->setParameter('{{ symbol }}', $value)
            ->addViolation();
        }
    }
}
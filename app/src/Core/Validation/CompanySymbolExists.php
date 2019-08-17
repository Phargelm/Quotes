<?php

namespace App\Core\Validation;

use Symfony\Component\Validator\Constraint;

class CompanySymbolExists extends Constraint
{
    public $message = 'Company symbol "{{ symbol }}" is not found.';
}
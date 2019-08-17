<?php

namespace App\Core\Exception;

class ConstraintValidationException extends \Exception
{
    private $errors;

    public function __construct(array $errors)
    {
        parent::__construct('Validation Error');
        $this->errors = $errors;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
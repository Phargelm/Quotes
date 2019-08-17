<?php

namespace App\Core\Serializing;

use Jawira\CaseConverter\Convert;
use Symfony\Component\Serializer\NameConverter\NameConverterInterface;

class SpinalCaseToCamelCaseConverter implements NameConverterInterface
{
    public function normalize($propertyName)
    {
        return (new Convert($propertyName))->toKebab();
    }

    public function denormalize($propertyName)
    {
        return (new Convert($propertyName))->toCamel();
    }
}
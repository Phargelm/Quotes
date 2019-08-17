<?php

namespace App\Core;

use App\Core\Exception\ConstraintValidationException;
use Jawira\CaseConverter\Convert;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RequestToDTOConverter implements ParamConverterInterface
{
    private $serializer;
    private $validator;

    public function __construct(SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
    }
    
    public function apply(Request $request, ParamConverter $configuration): void
    {
        $objectDTO = $this->getObjectDTO($request, $configuration->getClass());

        $errors = $this->validate($objectDTO);
        if (!empty($errors)) {
            throw new ConstraintValidationException($errors);
        }

        $request->attributes->set($configuration->getName(), $objectDTO);
    }

    public function supports(ParamConverter $configuration): bool
    {
        $type = $configuration->getOptions()['type'] ?? null;
        return !empty($configuration->getClass()) && $type == 'DTO';
    }

    private function validate(object $dto): ?array
    {
        $violations = $this->validator->validate($dto);

        if (count($violations) == 0) {
            return [];
        }

        $errors = [];
        foreach ($violations as $violation) {
            $property = (new Convert($violation->getPropertyPath()))->toKebab();
            $errors[$property][] = $violation->getMessage();
        }
        
        return $errors;
    }

    private function getObjectDTO(Request $request, string $class): object
    {
        if (!empty($request->getContent())) {
            return $this->serializer->deserialize($request->getContent(), $class, 'json');
        }

        return $this->serializer->denormalize($request->query->all(), $class);
    }
}
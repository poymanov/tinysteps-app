<?php

declare(strict_types=1);

namespace App\Serializer;

use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidationSerializer
{
    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function serialize(ConstraintViolationListInterface $violations): string
    {
        $data = [
            'message' => 'Ошибки валидации',
            'errors' => [],
        ];

        $nameConverter = new CamelCaseToSnakeCaseNameConverter();

        foreach ($violations as $violation) {
            /** @var ConstraintViolationInterface $violation */

            $property = $nameConverter->normalize($violation->getPropertyPath());
            $data['errors'][$property][] = $violation->getMessage();
        }

        return $this->serializer->serialize($data, 'json');
    }
}

<?php

declare(strict_types=1);

namespace App\Serializer;

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

        foreach ($violations as $violation) {
            /** @var ConstraintViolationInterface $violation */
            $data['errors'][$violation->getPropertyPath()][] = $violation->getMessage();
        }

        return $this->serializer->serialize($data, 'json');
    }
}

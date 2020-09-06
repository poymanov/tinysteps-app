<?php

declare(strict_types=1);

namespace App\Model\Lesson\UseCase\Teacher\Create;

use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Uuid(strict=false)
     */
    public $userId;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min="150")
     */
    public $description;

    /**
     * @var int
     * @Assert\NotBlank()
     * @Assert\Positive()
     */
    public $price;
}

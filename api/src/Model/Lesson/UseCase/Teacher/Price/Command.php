<?php

declare(strict_types=1);

namespace App\Model\Lesson\UseCase\Teacher\Price;

use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $id;

    /**
     * @var int
     * @Assert\NotBlank()
     * @Assert\Positive()
     */
    public $price;

    /**
     * @param string $id
     */
    public function __construct(string $id)
    {
        $this->id = $id;
    }
}

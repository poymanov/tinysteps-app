<?php

declare(strict_types=1);

namespace App\Model\Lesson\UseCase\Teacher\Rating;

use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $id;

    /**
     * @var float
     * @Assert\NotBlank()
     * @Assert\PositiveOrZero()
     * @Assert\LessThanOrEqual(value="5")
     */
    public $rating;

    /**
     * @param string $id
     */
    public function __construct(string $id)
    {
        $this->id = $id;
    }
}

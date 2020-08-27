<?php

declare(strict_types=1);

namespace App\Model\Lesson\UseCase\Goal\Create;

use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(max="255")
     */
    public $name;
}

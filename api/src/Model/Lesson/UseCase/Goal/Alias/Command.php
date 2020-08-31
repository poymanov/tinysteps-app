<?php

declare(strict_types=1);

namespace App\Model\Lesson\UseCase\Goal\Alias;

use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $id;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(max="255")
     */
    public $alias;

    /**
     * @param string $id
     */
    public function __construct(string $id)
    {
        $this->id = $id;
    }
}

<?php

declare(strict_types=1);

namespace App\Model\Lesson\UseCase\Lesson\Add;

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
     * @Assert\Uuid(strict=false)
     */
    public $scheduleId;

    /**
     * @param string $userId
     */
    public function __construct(string $userId)
    {
        $this->userId = $userId;
    }
}

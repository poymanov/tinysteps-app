<?php

declare(strict_types=1);

namespace App\Model\Lesson\UseCase\TeacherGoal\Add;

use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Uuid(strict=false)
     */
    public $goalId;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Uuid(strict=false)
     */
    public $teacherId;

    /**
     * @param string $teacherId
     */
    public function __construct(string $teacherId)
    {
        $this->teacherId = $teacherId;
    }
}

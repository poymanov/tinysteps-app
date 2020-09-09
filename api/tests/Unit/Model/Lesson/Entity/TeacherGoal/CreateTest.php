<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model\Lesson\Entity\TeacherGoal;

use App\Model\Lesson\Entity\TeacherGoal\Id;
use App\Model\Lesson\Entity\TeacherGoal\TeacherGoal;
use PHPUnit\Framework\TestCase;

class CreateTest extends TestCase
{
    public function testSuccess()
    {
        $teacherGoal = new TeacherGoal(
            $id = Id::next(),
            $teacherId = Id::next()->getValue(),
            $goalId = Id::next()->getValue(),
        );

        self::assertEquals($id, $teacherGoal->getId());
        self::assertEquals($teacherId, $teacherGoal->getTeacherId());
        self::assertEquals($goalId, $teacherGoal->getGoalId());
    }
}

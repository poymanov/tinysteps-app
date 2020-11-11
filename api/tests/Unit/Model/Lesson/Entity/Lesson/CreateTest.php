<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model\Lesson\Entity\Lesson;

use App\Model\Lesson\Entity\Lesson\Id;
use App\Model\Lesson\Entity\Lesson\Lesson;
use App\Model\Lesson\Entity\Schedule\Id as ScheduleId;
use App\Model\Lesson\Entity\Schedule\Schedule;
use App\Model\Lesson\Entity\Teacher\Alias;
use App\Tests\Builder\Lesson\TeacherBuilder;
use App\Tests\Builder\User\UserBuilder;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class CreateTest extends TestCase
{
    /**
     * Успешное создания
     */
    public function testSuccess()
    {
        $user = (new UserBuilder())->viaEmail()->build();
        $userId = $user->getId()->getValue();

        $teacher = (new TeacherBuilder())
            ->withAlias(new Alias('test'))
            ->build();

        $schedule = new Schedule(ScheduleId::next(), $teacher, new DateTimeImmutable(), new DateTimeImmutable());

        $lesson = new Lesson(
            $id = Id::next(),
            $userId,
            $schedule,
            $createdAt = new DateTimeImmutable(),
        );

        self::assertEquals($id, $lesson->getId());
        self::assertEquals($userId, $lesson->getUserId());
        self::assertEquals($schedule, $lesson->getSchedule());
        self::assertEquals($createdAt, $lesson->getCreatedAt());
    }
}

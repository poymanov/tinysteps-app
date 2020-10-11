<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model\Lesson\Entity\Schedule;

use App\Model\Lesson\Entity\Schedule\Id;
use App\Model\Lesson\Entity\Schedule\Schedule;
use App\Model\Lesson\Entity\Teacher\Alias;
use App\Tests\Builder\Lesson\TeacherBuilder;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class CreateTest extends TestCase
{
    /**
     * Успешное создания
     */
    public function testSuccess()
    {
        $teacher = (new TeacherBuilder())
            ->withAlias(new Alias('test'))
            ->build();

        $schedule = new Schedule(
            $id = Id::next(),
            $teacher,
            $date = new DateTimeImmutable(),
            $createdAt = new DateTimeImmutable()
        );

        self::assertEquals($id, $schedule->getId());
        self::assertEquals($teacher, $schedule->getTeacher());
        self::assertEquals($date, $schedule->getDate());
        self::assertEquals($createdAt, $schedule->getCreatedAt());
    }
}

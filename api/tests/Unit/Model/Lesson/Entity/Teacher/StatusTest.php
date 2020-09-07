<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model\Lesson\Entity\Teacher;

use App\Model\Lesson\Entity\Teacher\Alias;
use App\Model\Lesson\Entity\Teacher\Status;
use App\Tests\Builder\Lesson\TeacherBuilder;
use PHPUnit\Framework\TestCase;

class StatusTest extends TestCase
{
    /**
     * Использование статуса, не входящего в список допустимых статусов
     */
    public function testNotValid(): void
    {
        self::expectExceptionMessage('Неизвестный статус.');

        new Status('Test');
    }

    /**
     * Использование статуса, который уже установлен
     */
    public function testAlready(): void
    {
        $teacher = (new TeacherBuilder())
            ->withAlias(new Alias('test'))
            ->build();

        self::expectExceptionMessage('Преподаватель уже находится в данном статусе.');

        $teacher->changeStatus(Status::active());
    }

    /**
     * Успешное изменение статуса
     */
    public function testSuccess(): void
    {
        $teacher = (new TeacherBuilder())
            ->withAlias(new Alias('test'))
            ->build();

        $status = Status::archived();

        $teacher->changeStatus($status);

        self::assertEquals($status, $teacher->getStatus());
    }
}

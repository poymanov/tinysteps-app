<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model\Lesson\Entity\Goal;

use App\Model\Lesson\Entity\Goal\Status;
use App\Tests\Builder\Lesson\GoalBuilder;
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
     * Успешное изменение статуса
     */
    public function testSuccess(): void
    {
        $goal = (new GoalBuilder())->build();

        $status = Status::archived();

        $goal->changeStatus($status);

        self::assertEquals($status, $goal->getStatus());
    }
}

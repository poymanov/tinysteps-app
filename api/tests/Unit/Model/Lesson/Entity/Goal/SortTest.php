<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model\Lesson\Entity\Goal;

use App\Tests\Builder\Lesson\GoalBuilder;
use PHPUnit\Framework\TestCase;

class SortTest extends TestCase
{
    /**
     * Попытка задать нулевой порядок
     */
    public function testZero(): void
    {

        $goal = (new GoalBuilder())->build();

        self::expectExceptionMessage('Значение порядка должно быть больше нуля.');
        $goal->changeSort(0);
    }

    /**
     * Попытка задать отрицательный порядок
     */
    public function testLessThanZero(): void
    {
        $goal = (new GoalBuilder())->build();

        self::expectExceptionMessage('Значение порядка должно быть больше нуля.');
        $goal->changeSort(-1);
    }

    /**
     * Успешное изменение порядка
     */
    public function testSuccess(): void
    {
        $sort = 2;

        $goal = (new GoalBuilder())->build();
        $goal->changeSort($sort);

        self::assertEquals($sort, $goal->getSort());
    }
}

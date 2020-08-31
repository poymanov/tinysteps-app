<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model\Lesson\Entity\Goal;

use App\Model\Lesson\Entity\Goal\Alias;
use App\Tests\Builder\Lesson\GoalBuilder;
use PHPUnit\Framework\TestCase;

class AliasTest extends TestCase
{
    public function testNotValid(): void
    {
        self::expectExceptionMessage('Неправильный формат alias.');

        new Alias('Test Test');
    }

    public function testSuccess()
    {
        $goal = (new GoalBuilder())->build();

        $alias = new Alias('test-test');

        $goal->changeAlias($alias);

        self::assertEquals($alias, $goal->getAlias());

    }
}

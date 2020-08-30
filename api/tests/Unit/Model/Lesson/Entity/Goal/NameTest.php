<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model\Lesson\Entity\Goal;

use App\Tests\Builder\Lesson\GoalBuilder;
use Exception;
use PHPUnit\Framework\TestCase;

class NameTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testSuccess(): void
    {
        $goal = (new GoalBuilder())->build();

        $name = 'Прочие потребности';

        $goal->changeName($name);

        self::assertEquals($name, $goal->getName());
    }
}

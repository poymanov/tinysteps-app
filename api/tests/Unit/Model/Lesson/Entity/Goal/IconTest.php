<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model\Lesson\Entity\Goal;

use App\Tests\Builder\Lesson\GoalBuilder;
use Exception;
use PHPUnit\Framework\TestCase;

class IconTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testSuccess(): void
    {
        $goal = (new GoalBuilder())->build();

        $icon = '🏫';

        $goal->changeIcon($icon);

        self::assertEquals($icon, $goal->getIcon());
    }
}

<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model\Lesson\Entity\Goal;

use App\Model\Lesson\Entity\Goal\Alias;
use App\Model\Lesson\Entity\Goal\Goal;
use App\Model\Lesson\Entity\Goal\Id;
use App\Model\Lesson\Entity\Goal\Status;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class GoalTest extends TestCase
{
    public function testSuccess(): void
    {
        $goal = new Goal(
            $id = Id::next(),
            $alias = new Alias('alias'),
            $name = 'name',
            $sort = 1,
            $createdAt = new DateTimeImmutable(),
        );

        self::assertEquals($id, $goal->getId());
        self::assertEquals($name, $goal->getName());
        self::assertEquals($alias, $goal->getAlias());
        self::assertEquals($sort, $goal->getSort());
        self::assertTrue(Status::isEqualActive($goal->getStatus()->getValue()));
        self::assertEquals($createdAt, $goal->getCreatedAt());
    }
}

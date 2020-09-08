<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model\Lesson\Entity\Teacher;

use App\Model\Lesson\Entity\Teacher\Alias;
use App\Model\Lesson\Entity\Teacher\Rating;
use App\Model\Lesson\Entity\Teacher\Status;
use App\Tests\Builder\Lesson\TeacherBuilder;
use Exception;
use PHPUnit\Framework\TestCase;

class RatingTest extends TestCase
{
    /**
     * Указана отрицательный рейтинг
     */
    public function testLessThanZero(): void
    {
        self::expectExceptionMessage('Значение рейтинга не может быть отрицательным.');

        new Rating(-1);
    }

    /**
     * Указана отрицательная цена
     */
    public function testGreaterThanMax(): void
    {
        self::expectExceptionMessage('Значение рейтинга не может быть больше 5.');

        new Rating(10);
    }

    /**
     * Преподаватель недоступен для редактирования
     *
     * @throws Exception
     */
    public function testNotActive()
    {
        $teacher = (new TeacherBuilder())
            ->withAlias(new Alias('test'))
            ->withStatus(Status::archived())
            ->build();

        self::expectExceptionMessage('Преподаватель находится в архиве и недоступен для изменений.');

        $teacher->changeRating(new Rating(4));
    }

    /**
     * Указана минимальное значение рейтинга
     */
    public function testMin(): void
    {
        $teacher = (new TeacherBuilder())
            ->withAlias(new Alias('test'))
            ->build();

        $rating = new Rating(0);

        $teacher->changeRating($rating);

        self::assertEquals($rating, $teacher->getRating());
    }

    /**
     * Указана максимальное значение рейтинга
     */
    public function testMax(): void
    {
        $teacher = (new TeacherBuilder())
            ->withAlias(new Alias('test'))
            ->build();

        $rating = new Rating(5);

        $teacher->changeRating($rating);

        self::assertEquals($rating, $teacher->getRating());
    }
}

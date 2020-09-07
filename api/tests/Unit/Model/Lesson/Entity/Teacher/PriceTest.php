<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model\Lesson\Entity\Teacher;

use App\Model\Lesson\Entity\Teacher\Alias;
use App\Model\Lesson\Entity\Teacher\Price;
use App\Model\Lesson\Entity\Teacher\Status;
use App\Tests\Builder\Lesson\TeacherBuilder;
use Exception;
use PHPUnit\Framework\TestCase;

class PriceTest extends TestCase
{
    /**
     * Указана нулевая цена
     */
    public function testPriceZero(): void
    {
        self::expectExceptionMessage('Значение цены должно быть больше 0.');

        new Price(0);
    }

    /**
     * Указана отрицательная цена
     */
    public function testPriceLessThanZero(): void
    {
        self::expectExceptionMessage('Значение цены должно быть больше 0.');

        new Price(-1);
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

        $teacher->changePrice(new Price(150));
    }

    /**
     * Успешное изменение цены
     *
     * @throws Exception
     */
    public function testSuccess()
    {
        $teacher = (new TeacherBuilder())
            ->withAlias(new Alias('test'))
            ->build();

        $price = new Price(150);

        $teacher->changePrice($price);

        self::assertEquals($price, $teacher->getPrice());
    }
}

<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model\Lesson\Entity\Teacher;

use App\Model\Lesson\Entity\Teacher\Alias;
use App\Model\Lesson\Entity\Teacher\Description;
use App\Model\Lesson\Entity\Teacher\Id;
use App\Model\Lesson\Entity\Teacher\Price;
use App\Model\Lesson\Entity\Teacher\Teacher;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class CreateTest extends TestCase
{
    /**
     * Создание с нулевой ценой
     */
    public function testPriceZero()
    {
        self::expectExceptionMessage('Значение цены должно быть больше 0.');

        new Teacher(
            Id::next(),
            Id::next()->getValue(),
            new Alias('test'),
            new Description('Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient.'),
            new Price(0),
            new DateTimeImmutable()
        );
    }

    /**
     * Создание с отрицательной ценой
     */
    public function testPriceLessThanZero()
    {
        self::expectExceptionMessage('Значение цены должно быть больше 0.');

        new Teacher(
            Id::next(),
            Id::next()->getValue(),
            new Alias('test'),
            new Description('Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient.'),
            new Price(-1),
            new DateTimeImmutable()
        );
    }

    /**
     * Некорректный alias
     */
    public function testInvalidAlias()
    {
        self::expectExceptionMessage('Неправильный формат alias.');

        new Teacher(
            Id::next(),
            Id::next()->getValue(),
            new Alias('test Test'),
            new Description('Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient.'),
            new Price(10),
            new DateTimeImmutable()
        );
    }

    /**
     * Успешное создания
     */
    public function testSuccess()
    {
        $teacher = new Teacher(
            $id = Id::next(),
            $userId = Id::next()->getValue(),
            $alias = new Alias('test'),
            $description = new Description('Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient.'),
            $price = new Price(10),
            $createdAt = new DateTimeImmutable()
        );

        self::assertEquals($id, $teacher->getId());
        self::assertEquals($userId, $teacher->getUserId());
        self::assertEquals($alias, $teacher->getAlias());
        self::assertEquals($description, $teacher->getDescription());
        self::assertEquals($price, $teacher->getPrice());
        self::assertEquals($createdAt, $teacher->getCreatedAt());
    }
}

<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model\Lesson\Entity\Teacher;

use App\Model\Lesson\Entity\Teacher\Alias;
use App\Model\Lesson\Entity\Teacher\Description;
use App\Model\Lesson\Entity\Teacher\Status;
use App\Tests\Builder\Lesson\TeacherBuilder;
use Exception;
use PHPUnit\Framework\TestCase;

class DescriptionTest extends TestCase
{
    /**
     * Указано короткое описание
     */
    public function testShort(): void
    {
        self::expectExceptionMessage('Минимальная длина описания (символов) - 150');

        new Description('test');
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

        $teacher->changeDescription(new Description('Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient.'));
    }

    /**
     * Успешное изменение статуса
     *
     * @throws Exception
     */
    public function testSuccess()
    {
        $teacher = (new TeacherBuilder())
            ->withAlias(new Alias('test'))
            ->build();

        $description = new Description('Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient.');

        $teacher->changeDescription($description);

        self::assertEquals($description, $teacher->getDescription());
    }
}

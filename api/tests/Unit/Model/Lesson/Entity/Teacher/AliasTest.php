<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model\Lesson\Entity\Teacher;

use App\Model\Lesson\Entity\Teacher\Alias;
use App\Model\Lesson\Entity\Teacher\Status;
use App\Tests\Builder\Lesson\TeacherBuilder;
use PHPUnit\Framework\TestCase;

class AliasTest extends TestCase
{
    /**
     * Неправильно указанное значение
     */
    public function testNotValid(): void
    {
        self::expectExceptionMessage('Неправильный формат alias.');

        new Alias('Test Test');
    }

    /**
     * Преподаватель недоступен для редактирования
     *
     * @throws \Exception
     */
    public function testNotActive()
    {
        $teacher = (new TeacherBuilder())
            ->withAlias(new Alias('test'))
            ->withStatus(Status::archived())
            ->build();

        self::expectExceptionMessage('Преподаватель находится в архиве и недоступен для изменений.');

        $teacher->changeAlias(new Alias('test'));
    }

    /**
     * Успешное изменение alias
     */
    public function testSuccess()
    {
        $teacher = (new TeacherBuilder())
            ->withAlias(new Alias('test'))
            ->build();

        $alias = new Alias('test-test');

        $teacher->changeAlias($alias);

        self::assertEquals($alias, $teacher->getAlias());
    }
}

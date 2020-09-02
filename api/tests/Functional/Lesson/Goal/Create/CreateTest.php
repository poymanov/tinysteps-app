<?php

declare(strict_types=1);

namespace App\Tests\Functional\Lesson\Goal\Create;

use App\Tests\Functional\DbWebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CreateTest extends DbWebTestCase
{
    private const BASE_URL = '/goals/create';

    private const BASE_METHOD = Request::METHOD_POST;

    /**
     * Попытка GET-запроса
     */
    public function testInvalidMethod(): void
    {
        $this->assertInvalidMethod(Request::METHOD_GET, self::BASE_URL);
    }

    /**
     * Попытка создания цели не авторизованным пользователем
     */
    public function testNotAuth(): void
    {
        $this->assertNotAuth(self::BASE_METHOD, self::BASE_URL);
    }

    /**
     * Попытка создания цели пользователем без роли ROLE_ADMIN
     */
    public function testNotAdmin(): void
    {
        $this->assertNotAdmin(self::BASE_METHOD, self::BASE_URL);
    }

    /**
     * Название не заполнено
     */
    public function testEmpty(): void
    {
        $this->assertValidationFailed(
            self::BASE_METHOD,
            self::BASE_URL,
            [],
            ['name' => ['Значение не должно быть пустым.']]
        );
    }

    /**
     * Заполнено слишком длинное название
     */
    public function testTooLongName(): void
    {
        $this->assertValidationFailed(
            self::BASE_METHOD,
            self::BASE_URL,
            $this->getTooLongNameData(),
            ['name' => ['Значение слишком длинное. Должно быть равно 255 символам или меньше.']]
        );
    }

    /**
     * Цель с данным наименованием уже существует
     */
    public function testExisted(): void
    {
        $this->assertBadRequest(
            self::BASE_METHOD,
            self::BASE_URL,
            $this->getExistedData(),
            'Цель с таким наименованием уже существует.'
        );
    }

    /**
     * Успешное создание цели
     */
    public function testSuccess(): void
    {
        $this->authAsAdmin();

        $this->postWithContent(self::BASE_URL, $this->getSuccessData());

        $data = $this->getJsonData(Response::HTTP_CREATED);

        self::assertEmpty($data);
        $this->assertIsInDatabase('lesson_goals', [
            'alias'  => 'procie-potrebnosti',
            'name'   => 'Прочие потребности',
            'status' => 'active',
            'sort'   => 6,
        ]);
    }

    /**
     * Данные с длинным названием цели
     */
    public function getTooLongNameData(): array
    {
        return [
            'name' => $this->getRandomString(),
        ];
    }

    /**
     * Данные для существующей цели
     */
    public function getExistedData(): array
    {
        return [
            'name' => 'Для учебы',
        ];
    }

    /**
     * Данные для успешного создания цели
     */
    public function getSuccessData(): array
    {
        return [
            'name' => 'Прочие потребности',
        ];
    }
}

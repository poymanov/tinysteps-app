<?php

declare(strict_types=1);

namespace App\Tests\Functional\Lesson\Goal;

use App\Tests\Functional\DbWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class CreateTest extends DbWebTestCase
{
    private const BASE_URL = '/goals/create';

    /**
     * Попытка GET-запроса
     */
    public function testInvalidMethod(): void
    {
        $this->client->request('GET', self::BASE_URL);
        self::assertResponseStatusCodeSame(Response::HTTP_METHOD_NOT_ALLOWED);
    }

    /**
     * Попытка создания цели не авторизованным пользователем
     */
    public function testNotAuth(): void
    {
        $this->client->request('POST', self::BASE_URL);

        self::assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Попытка создания цели пользователем без роли ROLE_ADMIN
     */
    public function testNotAdmin(): void
    {
        $this->authAsUser();

        $this->client->request('POST', self::BASE_URL);

        self::assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);

        $data = $this->getJsonData();

        self::assertEquals([
            'error' => [
                'message' => 'Вам запрещено выполнять данное действие',
            ],
        ], $data);
    }

    /**
     * Название не заполнено
     */
    public function testEmpty(): void
    {
        $this->authAsAdmin();

        $this->postWithContent(self::BASE_URL, []);

        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);

        $data = $this->getJsonData();

        self::assertEquals([
            'message' => 'Ошибки валидации',
            'errors'  => [
                'name' => ['Значение не должно быть пустым.'],
            ],
        ], $data);
    }

    /**
     * Заполнено слишком длинное название
     */
    public function testTooLongName(): void
    {
        $this->authAsAdmin();

        $this->postWithContent(self::BASE_URL, $this->getTooLongNameData());

        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);

        $data = $this->getJsonData();

        self::assertEquals([
            'message' => 'Ошибки валидации',
            'errors'  => [
                'name' => ['Значение слишком длинное. Должно быть равно 255 символам или меньше.'],
            ],
        ], $data);
    }

    /**
     * Цель с данным наименованием уже существует
     */
    public function testExisted(): void
    {
        $this->authAsAdmin();

        $this->postWithContent(self::BASE_URL, $this->getExistedData());

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);

        $data = $this->getJsonData();

        self::assertEquals([
            'error' => [
                'message' => 'Цель с таким наименованием уже существует.',
            ],
        ], $data);
    }

    /**
     * Успешное создание цели
     */
    public function testSuccess(): void
    {
        $this->authAsAdmin();

        $this->postWithContent(self::BASE_URL, $this->getSuccessData());

        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $data = $this->getJsonData();

        self::assertEmpty($data);
        $this->assertIsInDatabase('lesson_goals', [
            'alias'  => 'procie-potrebnosti',
            'name'   => 'Прочие потребности',
            'status' => 'active',
            'sort'   => 5,
        ]);
    }

    /**
     * Данные с длинным названием цели
     */
    public function getTooLongNameData(): array
    {
        return [
            'name' => bin2hex(openssl_random_pseudo_bytes(150)),
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

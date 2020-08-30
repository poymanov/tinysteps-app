<?php

declare(strict_types=1);

namespace App\Tests\Functional\Lesson\Goal;

use App\Tests\Fixtures\GoalFixture;
use App\Tests\Functional\DbWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class NameTest extends DbWebTestCase
{
    private const BASE_URL = '/goals/' . GoalFixture::GOAL_1_ID . '/change-name';

    /**
     * Попытка GET-запроса
     */
    public function testInvalidMethod(): void
    {
        $this->client->request('GET', self::BASE_URL);
        self::assertResponseStatusCodeSame(Response::HTTP_METHOD_NOT_ALLOWED);
    }

    /**
     * Попытка просмотра цели без аутентификации
     */
    public function testNotAuth(): void
    {
        $this->client->request('PATCH', self::BASE_URL);

        self::assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Попытка выполнения запроса пользователем без прав администратора
     */
    public function testNotAdmin(): void
    {
        $this->authAsUser();

        $this->client->request('PATCH', self::BASE_URL);

        self::assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);

        $data = $this->getJsonData();

        self::assertEquals([
            'error' => [
                'message' => 'Вам запрещено выполнять данное действие',
            ],
        ], $data);
    }

    /**
     * Запрос цели обучения c uuid в неправильном формате
     */
    public function testNotValidUuid(): void
    {
        $this->client->request('PATCH', '/goals/123/change-name');

        $data = $this->getJsonData();

        self::assertResponseStatusCodeSame(Response::HTTP_INTERNAL_SERVER_ERROR);

        self::assertEquals([
            'error' => [
                'message' => 'Ошибка запроса к базе данных',
            ],
        ], $data);
    }

    /**
     * Запрос несуществующей цели обучения
     */
    public function testNotFound(): void
    {
        $this->client->request('PATCH', '/goals/00000000-0000-0000-0000-000000000099/change-name');

        self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    /**
     * Значение нового имени не указано
     */
    public function testEmpty(): void
    {
        $this->authAsAdmin();

        $this->patchWithContent(self::BASE_URL, []);

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
     * Значение нового имени слишком длинное
     */
    public function testTooLongName(): void
    {
        $this->authAsAdmin();

        $this->patchWithContent(self::BASE_URL, $this->getTooLongNameData());

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
     * Цель с указанным именем уже существует
     */
    public function testExisted(): void
    {
        $this->authAsAdmin();

        $this->patchWithContent(self::BASE_URL, $this->getExistedData());

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);

        $data = $this->getJsonData();

        self::assertEquals([
            'error' => [
                'message' => 'Цель с таким наименованием уже существует.',
            ],
        ], $data);
    }

    /**
     * Успешное изменение имени
     */
    public function testSuccess(): void
    {
        $this->authAsAdmin();

        $this->patchWithContent(self::BASE_URL, $this->getSuccessData());

        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        $data = $this->getJsonData();

        self::assertEmpty($data);

        $this->assertIsInDatabase('lesson_goals', [
            'id'   => GoalFixture::GOAL_1_ID,
            'name' => 'Прочие потребности',
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

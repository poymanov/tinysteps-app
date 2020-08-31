<?php

declare(strict_types=1);

namespace App\Tests\Functional\Lesson\Goal;

use App\Tests\Fixtures\GoalFixture;
use App\Tests\Functional\DbWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class StatusTest extends DbWebTestCase
{
    private const BASE_URL = '/goals/update/status/' . GoalFixture::GOAL_1_ID;

    /**
     * Попытка GET-запроса
     */
    public function testInvalidMethod(): void
    {
        $this->client->request('GET', self::BASE_URL);
        self::assertResponseStatusCodeSame(Response::HTTP_METHOD_NOT_ALLOWED);
    }

    /**
     * Попытка выполнения запроса без аутентификации
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
     * Изменение статуса цели обучения c uuid в неправильном формате
     */
    public function testNotValidUuid(): void
    {
        $this->client->request('PATCH', '/goals/update/status/123');

        $data = $this->getJsonData();

        self::assertResponseStatusCodeSame(Response::HTTP_INTERNAL_SERVER_ERROR);

        self::assertEquals([
            'error' => [
                'message' => 'Ошибка запроса к базе данных',
            ],
        ], $data);
    }

    /**
     * Попытка изменения статуса несуществующей цели обучения
     */
    public function testNotFound(): void
    {
        $this->client->request('PATCH', '/goals/update/status/00000000-0000-0000-0000-000000000099');

        self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    /**
     * Значение нового статуса не указано
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
                'status' => ['Значение не должно быть пустым.'],
            ],
        ], $data);
    }

    /**
     * Значение нового статуса не входит в список допустимых статусов
     */
    public function testNotValid(): void
    {
        $this->authAsAdmin();

        $this->patchWithContent(self::BASE_URL, $this->getNotValidData());

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);

        $data = $this->getJsonData();

        self::assertEquals([
            'error' => [
                'message' => 'Неизвестный статус.',
            ],
        ], $data);
    }

    /**
     * Успешное изменение статуса
     */
    public function testSuccess(): void
    {
        $this->authAsAdmin();

        $this->patchWithContent(self::BASE_URL, $this->getSuccessData());

        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        $data = $this->getJsonData();

        self::assertEmpty($data);

        $this->assertIsInDatabase('lesson_goals', [
            'id'     => GoalFixture::GOAL_1_ID,
            'status' => 'archived',
        ]);
    }

    /**
     * Данные для некорректного статуса
     */
    public function getNotValidData(): array
    {
        return [
            'status' => 'Test',
        ];
    }

    /**
     * Данные для успешного изменения статуса
     */
    public function getSuccessData(): array
    {
        return [
            'status' => 'archived',
        ];
    }
}

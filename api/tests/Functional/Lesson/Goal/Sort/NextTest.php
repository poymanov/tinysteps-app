<?php

declare(strict_types=1);

namespace App\Tests\Functional\Lesson\Goal\Sort;

use App\Tests\Fixtures\GoalFixture;
use App\Tests\Functional\DbWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class NextTest extends DbWebTestCase
{
    private const BASE_URL = '/goals/sort/next/' . GoalFixture::GOAL_1_ID;

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
     * Изменение порядка цели обучения c uuid в неправильном формате
     */
    public function testNotValidUuid(): void
    {
        $this->client->request('PATCH', '/goals/sort/next/123');

        $data = $this->getJsonData();

        self::assertResponseStatusCodeSame(Response::HTTP_INTERNAL_SERVER_ERROR);

        self::assertEquals([
            'error' => [
                'message' => 'Ошибка запроса к базе данных',
            ],
        ], $data);
    }

    /**
     * Попытка изменения порядка несуществующей цели обучения
     */
    public function testNotFound(): void
    {
        $this->client->request('PATCH', '/goals/sort/next/00000000-0000-0000-0000-000000000099');

        self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    /**
     * Перемещение невозможно. Цель является последней
     */
    public function testLast(): void
    {
        $this->authAsAdmin();

        $this->client->request('PATCH', '/goals/sort/next/' . GoalFixture::GOAL_5_ID);

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);

        $data = $this->getJsonData();

        self::assertEquals([
            'error' => [
                'message' => 'Перемещение невозможно. Цель является последней.',
            ],
        ], $data);
    }

    /**
     * Успешное перемещение вперед
     */
    public function testSuccess(): void
    {
        $this->authAsAdmin();

        $this->client->request('PATCH', self::BASE_URL);

        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        $data = $this->getJsonData();

        self::assertEmpty($data);

        $this->assertIsInDatabase('lesson_goals', [
            'id'   => GoalFixture::GOAL_1_ID,
            'sort' => 2,
        ]);

        $this->assertIsInDatabase('lesson_goals', [
            'id'   => GoalFixture::GOAL_2_ID,
            'sort' => 1,
        ]);
    }
}

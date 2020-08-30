<?php

declare(strict_types=1);

namespace App\Tests\Functional\Lesson\Goal;

use App\Tests\Fixtures\GoalFixture;
use App\Tests\Functional\DbWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ShowTest extends DbWebTestCase
{
    private const BASE_URL = '/goals/' . GoalFixture::GOAL_1_ID;

    /**
     * Попытка просмотра цели без аутентификации
     */
    public function testNotAuth(): void
    {
        $this->client->request('GET', self::BASE_URL);

        self::assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Попытка выполнения запроса пользователем без прав администратора
     */
    public function testNotAdmin(): void
    {
        $this->authAsUser();

        $this->client->request('GET', self::BASE_URL);

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
        $this->client->request('GET', '/goals/123');

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
        $this->client->request('GET', '/goals/00000000-0000-0000-0000-000000000099');

        self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    /**
     * Успешный запрос цели обучения
     */
    public function testSuccess(): void
    {
        $this->authAsAdmin();

        $this->client->request('GET', self::BASE_URL);

        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        $data = $this->getJsonData();

        self::assertEquals([
            'id' => GoalFixture::GOAL_1_ID,
            'alias' => 'dla-pereezda',
            'name' => 'Для переезда',
            'status' => 'active',
            'sort' => 1,
            'created_at' => '2020-01-01 10:00',
        ], $data);
    }
}

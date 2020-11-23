<?php

declare(strict_types=1);

namespace App\Tests\Functional\Lesson\Goal\Update\Sort;

use App\DataFixtures\GoalFixture;
use App\Tests\Functional\DbWebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class NextTest extends DbWebTestCase
{
    private const BASE_URL = '/goals/sort/next/';

    private const BASE_URL_GOAL_1 = '/goals/sort/next/' . GoalFixture::GOAL_1_ID;

    private const BASE_METHOD = Request::METHOD_PATCH;

    /**
     * Попытка GET-запроса
     */
    public function testInvalidMethod(): void
    {
        $this->assertInvalidMethod(Request::METHOD_GET, self::BASE_URL_GOAL_1);
    }

    /**
     * Попытка выполнения запроса без аутентификации
     */
    public function testNotAuth(): void
    {
        $this->assertNotAuth(self::BASE_METHOD, self::BASE_URL_GOAL_1);
    }

    /**
     * Попытка выполнения запроса пользователем без прав администратора
     */
    public function testNotAdmin(): void
    {
        $this->assertNotAdmin(self::BASE_METHOD, self::BASE_URL_GOAL_1);
    }

    /**
     * Изменение порядка цели обучения c uuid в неправильном формате
     */
    public function testNotValidUuid(): void
    {
        $this->assertNotValidUuid(self::BASE_METHOD, self::BASE_URL . '123');
    }

    /**
     * Попытка изменения порядка несуществующей цели обучения
     */
    public function testNotFound(): void
    {
        $this->assertNotFound(self::BASE_METHOD, self::BASE_URL . '00000000-0000-0000-0000-000000000099');
    }

    /**
     * Перемещение невозможно. Цель является последней
     */
    public function testLast(): void
    {
        $this->authAsAdmin();

        $this->client->request(self::BASE_METHOD, self::BASE_URL . GoalFixture::GOAL_5_ID);

        $data = $this->getJsonData(Response::HTTP_BAD_REQUEST);

        self::assertEquals([
            'message' => 'Перемещение невозможно. Цель является последней.',
            'errors' => [],
        ], $data);
    }

    /**
     * Успешное перемещение вперед
     */
    public function testSuccess(): void
    {
        $this->authAsAdmin();

        $this->client->request(self::BASE_METHOD, self::BASE_URL_GOAL_1);

        $data = $this->getJsonData(Response::HTTP_OK);

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

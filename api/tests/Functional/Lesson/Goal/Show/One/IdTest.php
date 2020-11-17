<?php

declare(strict_types=1);

namespace App\Tests\Functional\Lesson\Goal\Show\One;

use App\Tests\Fixtures\GoalFixture;
use App\Tests\Functional\DbWebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class IdTest extends DbWebTestCase
{
    private const BASE_URL = '/goals/show/one/id/';

    private const BASE_URL_GOAL_1 = self::BASE_URL . GoalFixture::GOAL_1_ID;

    private const BASE_METHOD = Request::METHOD_GET;

    /**
     * Попытка просмотра цели без аутентификации
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
     * Запрос цели обучения c uuid в неправильном формате
     */
    public function testNotValidUuid(): void
    {
        $this->assertNotValidUuid(self::BASE_METHOD, self::BASE_URL . '123');
    }

    /**
     * Запрос несуществующей цели обучения
     */
    public function testNotFound(): void
    {
        $this->assertNotFound(self::BASE_METHOD, self::BASE_URL . '00000000-0000-0000-0000-000000000099');
    }

    /**
     * Успешный запрос цели обучения
     */
    public function testSuccess(): void
    {
        $this->authAsAdmin();

        $this->client->request(self::BASE_METHOD, self::BASE_URL_GOAL_1);

        $data = $this->getJsonData(Response::HTTP_OK);

        self::assertEquals([
            'id'         => GoalFixture::GOAL_1_ID,
            'alias'      => 'dla-pereezda',
            'name'       => 'Для переезда',
            'status'     => 'active',
            'sort'       => 1,
            'icon'       => '🚜',
            'created_at' => '2020-01-01 10:00:00',
        ], $data);
    }
}

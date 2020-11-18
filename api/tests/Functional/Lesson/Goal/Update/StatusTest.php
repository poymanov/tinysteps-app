<?php

declare(strict_types=1);

namespace App\Tests\Functional\Lesson\Goal\Update;

use App\DataFixtures\GoalFixture;
use App\Tests\Functional\DbWebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class StatusTest extends DbWebTestCase
{
    private const BASE_URL = '/goals/update/status/';

    private const BASE_URL_GOAL_1 = self::BASE_URL . GoalFixture::GOAL_1_ID;

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
     * Изменение статуса цели обучения c uuid в неправильном формате
     */
    public function testNotValidUuid(): void
    {
        $this->assertNotValidUuid(self::BASE_METHOD, self::BASE_URL . '123');
    }

    /**
     * Попытка изменения статуса несуществующей цели обучения
     */
    public function testNotFound(): void
    {
        $this->assertNotFound(self::BASE_METHOD, self::BASE_URL . '00000000-0000-0000-0000-000000000099');
    }

    /**
     * Значение нового статуса не указано
     */
    public function testEmpty(): void
    {
        $this->assertValidationFailed(
            self::BASE_METHOD,
            self::BASE_URL_GOAL_1,
            [],
            [
                'status' => ['Значение не должно быть пустым.'],
            ]
        );
    }

    /**
     * Значение нового статуса не входит в список допустимых статусов
     */
    public function testNotValid(): void
    {
        $this->assertBadRequest(
            self::BASE_METHOD,
            self::BASE_URL_GOAL_1,
            $this->getNotValidData(),
            'Неизвестный статус.'
        );
    }

    /**
     * Успешное изменение статуса
     */
    public function testSuccess(): void
    {
        $this->authAsAdmin();

        $this->patchWithContent(self::BASE_URL_GOAL_1, $this->getSuccessData());

        $data = $this->getJsonData(Response::HTTP_OK);

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

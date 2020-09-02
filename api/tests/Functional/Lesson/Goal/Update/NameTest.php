<?php

declare(strict_types=1);

namespace App\Tests\Functional\Lesson\Goal\Update;

use App\Tests\Fixtures\GoalFixture;
use App\Tests\Functional\DbWebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class NameTest extends DbWebTestCase
{
    private const BASE_URL = '/goals/update/name/';

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
     * Изменение названия цели обучения c uuid в неправильном формате
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
     * Значение нового имени не указано
     */
    public function testEmpty(): void
    {
        $this->assertValidationFailed(
            self::BASE_METHOD,
            self::BASE_URL_GOAL_1,
            [],
            [
                'name' => ['Значение не должно быть пустым.'],
            ]
        );
    }

    /**
     * Значение нового имени слишком длинное
     */
    public function testTooLongName(): void
    {
        $this->assertValidationFailed(
            self::BASE_METHOD,
            self::BASE_URL_GOAL_1,
            $this->getTooLongNameData(),
            [
                'name' => ['Значение слишком длинное. Должно быть равно 255 символам или меньше.'],
            ]
        );
    }

    /**
     * Цель с указанным именем уже существует
     */
    public function testExisted(): void
    {
        $this->assertBadRequest(
            self::BASE_METHOD,
            self::BASE_URL_GOAL_1,
            $this->getExistedData(),
            'Цель с таким наименованием уже существует.'
        );
    }

    /**
     * Успешное изменение имени
     */
    public function testSuccess(): void
    {
        $this->authAsAdmin();

        $this->patchWithContent(self::BASE_URL_GOAL_1, $this->getSuccessData());

        $data = $this->getJsonData(Response::HTTP_OK);

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

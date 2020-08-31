<?php

declare(strict_types=1);

namespace App\Tests\Functional\Lesson\Goal;

use App\Tests\Fixtures\GoalFixture;
use App\Tests\Functional\DbWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class AliasTest extends DbWebTestCase
{
    private const BASE_URL = '/goals/update/alias/' . GoalFixture::GOAL_1_ID;

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
     * Изменение alias цели обучения c uuid в неправильном формате
     */
    public function testNotValidUuid(): void
    {
        $this->client->request('PATCH', '/goals/update/alias/123');

        $data = $this->getJsonData();

        self::assertResponseStatusCodeSame(Response::HTTP_INTERNAL_SERVER_ERROR);

        self::assertEquals([
            'error' => [
                'message' => 'Ошибка запроса к базе данных',
            ],
        ], $data);
    }

    /**
     * Попытка изменения alias несуществующей цели обучения
     */
    public function testNotFound(): void
    {
        $this->client->request('PATCH', '/goals/update/alias/00000000-0000-0000-0000-000000000099');

        self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    /**
     * Значение нового alias не указано
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
                'alias' => ['Значение не должно быть пустым.'],
            ],
        ], $data);
    }

    /**
     * Значение нового alias слишком длинное
     */
    public function testTooLongAlias(): void
    {
        $this->authAsAdmin();

        $this->patchWithContent(self::BASE_URL, $this->getTooLongAliasData());

        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);

        $data = $this->getJsonData();

        self::assertEquals([
            'message' => 'Ошибки валидации',
            'errors'  => [
                'alias' => ['Значение слишком длинное. Должно быть равно 255 символам или меньше.'],
            ],
        ], $data);
    }

    /**
     * Значение нового alias некорректное
     */
    public function testNotValid(): void
    {
        $this->authAsAdmin();

        $this->patchWithContent(self::BASE_URL, $this->getNotValidData());

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);

        $data = $this->getJsonData();

        self::assertEquals([
            'error' => [
                'message' => 'Неправильный формат alias.',
            ],
        ], $data);
    }

    /**
     * Цель с указанным alias уже существует
     */
    public function testExisted(): void
    {
        $this->authAsAdmin();

        $this->patchWithContent(self::BASE_URL, $this->getExistedData());

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);

        $data = $this->getJsonData();

        self::assertEquals([
            'error' => [
                'message' => 'Цель с таким alias уже существует.',
            ],
        ], $data);
    }

    /**
     * Успешное изменение alias
     */
    public function testSuccess(): void
    {
        $this->authAsAdmin();

        $this->patchWithContent(self::BASE_URL, $this->getSuccessData());

        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        $data = $this->getJsonData();

        self::assertEmpty($data);

        $this->assertIsInDatabase('lesson_goals', [
            'id'    => GoalFixture::GOAL_1_ID,
            'alias' => 'test-test',
        ]);
    }

    /**
     * Данные с длинным alias
     */
    public function getTooLongAliasData(): array
    {
        return [
            'alias' => bin2hex(openssl_random_pseudo_bytes(150)),
        ];
    }

    /**
     * Данные для некорректного alias
     */
    public function getNotValidData(): array
    {
        return [
            'alias' => 'Test Test',
        ];
    }

    /**
     * Данные для существующего alias
     */
    public function getExistedData(): array
    {
        return [
            'alias' => 'dla-uceby',
        ];
    }

    /**
     * Данные для успешного создания цели
     */
    public function getSuccessData(): array
    {
        return [
            'alias' => 'test-test',
        ];
    }
}

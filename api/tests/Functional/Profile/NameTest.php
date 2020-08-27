<?php

declare(strict_types=1);

namespace App\Tests\Functional\Profile;

use App\DataFixtures\UserFixture;
use App\Tests\Functional\DbWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class NameTest extends DbWebTestCase
{
    private const BASE_URL = '/profile/name';

    /**
     * Попытка GET-запроса
     */
    public function testInvalidMethod(): void
    {
        $this->client->request('GET', self::BASE_URL);
        self::assertResponseStatusCodeSame(Response::HTTP_METHOD_NOT_ALLOWED);
    }

    /**
     * Попытка получение профиля неавторизованным пользователем
     */
    public function testNotAuth(): void
    {
        $this->client->request('PATCH', self::BASE_URL);

        self::assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Имя и фамилия не отправлены
     */
    public function testEmpty(): void
    {
        $this->authAsUser();

        $this->patchWithContent(self::BASE_URL, []);

        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);

        $data = $this->getJsonData();

        self::assertEquals([
            'message' => 'Ошибки валидации',
            'errors'  => [
                'first' => ['Значение не должно быть пустым.'],
                'last'  => ['Значение не должно быть пустым.'],
            ],
        ], $data);
    }

    /**
     * Заполнено слишком длинное имя
     */
    public function testTooLongFirstName(): void
    {
        $this->authAsUser();

        $this->patchWithContent(self::BASE_URL, $this->getTooLongFirstNameData());

        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);

        $data = $this->getJsonData();

        self::assertEquals([
            'message' => 'Ошибки валидации',
            'errors'  => [
                'first' => ['Значение слишком длинное. Должно быть равно 255 символам или меньше.'],
            ],
        ], $data);
    }

    /**
     * Заполнено слишком длинная фамилия
     */
    public function testTooLongLastName(): void
    {
        $this->authAsUser();

        $this->patchWithContent(self::BASE_URL, $this->getTooLongLastNameData());

        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);

        $data = $this->getJsonData();

        self::assertEquals([
            'message' => 'Ошибки валидации',
            'errors'  => [
                'last' => ['Значение слишком длинное. Должно быть равно 255 символам или меньше.'],
            ],
        ], $data);
    }

    /**
     * Успешное изменение имени
     */
    public function testSuccess(): void
    {
        $this->authAsUser();

        $this->patchWithContent(self::BASE_URL, $this->getSuccessData());

        self::assertResponseIsSuccessful();

        self::assertEmpty($this->getJsonData());

        $this->assertIsInDatabase('user_users', [
            'id' => UserFixture::USER_1_ID,
            'name_first' => 'test',
            'name_last' => 'test',
        ]);
    }

    /**
     * Данные для успешного запроса
     *
     * @return array
     */
    public function getSuccessData(): array
    {
        return [
            'first' => 'test',
            'last'  => 'test',
        ];
    }

    /**
     * Данные со длинным именем пользователя
     */
    public function getTooLongFirstNameData(): array
    {
        return array_merge($this->getSuccessData(), [
            'first' => bin2hex(openssl_random_pseudo_bytes(150)),
        ]);
    }

    /**
     * Данные со длинным именем пользователя
     */
    public function getTooLongLastNameData(): array
    {
        return array_merge($this->getSuccessData(), [
            'last' => bin2hex(openssl_random_pseudo_bytes(150)),
        ]);
    }
}

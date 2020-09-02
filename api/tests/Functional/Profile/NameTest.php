<?php

declare(strict_types=1);

namespace App\Tests\Functional\Profile;

use App\DataFixtures\UserFixture;
use App\Tests\Functional\DbWebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class NameTest extends DbWebTestCase
{
    private const BASE_URL = '/profile/name';

    /**
     * Попытка GET-запроса
     */
    public function testInvalidMethod(): void
    {
        $this->client->request(Request::METHOD_GET, self::BASE_URL);
        self::assertResponseStatusCodeSame(Response::HTTP_METHOD_NOT_ALLOWED);
    }

    /**
     * Попытка получение профиля неавторизованным пользователем
     */
    public function testNotAuth(): void
    {
        $this->client->request(Request::METHOD_PATCH, self::BASE_URL);

        self::assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Имя и фамилия не отправлены
     */
    public function testEmpty(): void
    {
        $this->assertValidationFailed(
            Request::METHOD_PATCH,
            self::BASE_URL,
            [],
            [
                'first' => ['Значение не должно быть пустым.'],
                'last'  => ['Значение не должно быть пустым.'],
            ]
        );
    }

    /**
     * Заполнено слишком длинное имя
     */
    public function testTooLongFirstName(): void
    {
        $this->assertValidationFailed(
            Request::METHOD_PATCH,
            self::BASE_URL,
            $this->getTooLongFirstNameData(),
            [
                'first' => ['Значение слишком длинное. Должно быть равно 255 символам или меньше.'],
            ]
        );
    }

    /**
     * Заполнено слишком длинная фамилия
     */
    public function testTooLongLastName(): void
    {
        $this->assertValidationFailed(
            Request::METHOD_PATCH,
            self::BASE_URL,
            $this->getTooLongLastNameData(),
            [
                'last' => ['Значение слишком длинное. Должно быть равно 255 символам или меньше.'],
            ]
        );
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
            'id'         => UserFixture::USER_1_ID,
            'name_first' => 'test',
            'name_last'  => 'test',
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
            'first' => $this->getRandomString(),
        ]);
    }

    /**
     * Данные со длинным именем пользователя
     */
    public function getTooLongLastNameData(): array
    {
        return array_merge($this->getSuccessData(), [
            'last' => $this->getRandomString(),
        ]);
    }
}

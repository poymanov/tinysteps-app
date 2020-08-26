<?php

declare(strict_types=1);

namespace App\Tests\Functional\OAuth;

use App\Tests\Functional\DbWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class PasswordGrantTest extends DbWebTestCase
{
    private const BASE_URL = '/token';

    /**
     * Попытка GET-запроса
     */
    public function testMethod(): void
    {
        $this->client->request('GET', self::BASE_URL);

        self::assertResponseStatusCodeSame(Response::HTTP_METHOD_NOT_ALLOWED);
    }

    /**
     * Несуществующий email для аутентификации
     */
    public function testNotExistedEmail(): void
    {
        $this->client->request('POST', self::BASE_URL, $this->getNotExistedEmailData());

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);

        $data = $this->getJsonData();

        self::assertEquals([
            'error' => [
                'message' => 'Пользователь не найден.',
            ],
        ], $data);
    }

    /**
     * Неправильный пароль для аутентификации
     */
    public function testInvalidPassword(): void
    {
        $this->client->request('POST', self::BASE_URL, $this->getInvalidPasswordData());

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);

        $data = $this->getJsonData();

        self::assertArrayHasKey('error', $data);
        self::assertNotEmpty($data['error']);
        self::assertEquals('invalid_grant', $data['error']);
    }

    /**
     * Успешная аутентификация
     */
    public function testSuccess(): void
    {
        $this->client->request('POST', self::BASE_URL, $this->getSuccessData());

        self::assertResponseIsSuccessful();

        $data = $this->getJsonData();

        self::assertArrayHasKey('token_type', $data);
        self::assertNotEmpty($data['token_type']);
        self::assertEquals('Bearer', $data['token_type']);

        self::assertArrayHasKey('expires_in', $data);
        self::assertNotEmpty($data['expires_in']);

        self::assertArrayHasKey('access_token', $data);
        self::assertNotEmpty($data['access_token']);

        self::assertArrayHasKey('refresh_token', $data);
        self::assertNotEmpty($data['refresh_token']);
    }

    /**
     * Получение данных для успешного запроса
     *
     * @return array
     */
    public function getSuccessData(): array
    {
        return [
            'grant_type'    => 'password',
            'username'      => 'user@app.test',
            'password'      => '123qwe',
            'client_id'     => 'oauth',
            'client_secret' => 'secret',
        ];
    }

    /**
     * Получение данных с несуществующим email
     *
     * @return array
     */
    public function getNotExistedEmailData(): array
    {
        return array_merge($this->getSuccessData(), [
            'username' => 'not-existed-email@app.test',
        ]);
    }

    /**
     * Получение данных с неправильным паролем
     *
     * @return array
     */
    public function getInvalidPasswordData(): array
    {
        return array_merge($this->getSuccessData(), [
            'password' => 'invalid',
        ]);
    }
}

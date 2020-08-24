<?php

declare(strict_types=1);

namespace App\Tests\Functional\Auth\Reset;

use App\Tests\Functional\DbWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ResetTest extends DbWebTestCase
{
    private const BASE_URL = '/auth/reset';

    /**
     * Попытка GET-запроса
     */
    public function testInvalidMethod()
    {
        $this->client->request('GET', self::BASE_URL);
        self::assertResponseStatusCodeSame(Response::HTTP_METHOD_NOT_ALLOWED);
    }

    /**
     * Пароль слишком короткий
     */
    public function testShortPassword(): void
    {
        $this->postWithContent(self::BASE_URL . '/123', $this->getNotValidPasswordData());

        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);

        $data = $this->getJsonData();

        self::assertEquals([
            'message' => 'Ошибки валидации',
            'errors'  => [
                'password' => ['Значение слишком короткое. Должно быть равно 6 символам или больше.'],
            ],
        ], $data);
    }

    /**
     * Попытка установки пароля по неизвестному токену;
     */
    public function testNotExisted(): void
    {
        $this->postWithContent(self::BASE_URL . '/1234', $this->getSuccessData());

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);

        $data = $this->getJsonData();

        self::assertEquals([
            'error' => [
                'message' => 'Неизвестный токен.',
            ],
        ], $data);
    }

    /**
     * Попытка подтверждения истекшего токена
     */
    public function testExpiredToken()
    {
        $this->postWithContent(self::BASE_URL . '/456', $this->getSuccessData());

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);

        $data = $this->getJsonData();

        self::assertEquals([
            'error' => [
                'message' => 'Токен сброса пароля уже истек.',
            ],
        ], $data);
    }

    /**
     * Успешный сброс пароля
     */
    public function testSuccess()
    {
        $this->postWithContent(self::BASE_URL . '/123', $this->getSuccessData());

        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        $data = $this->getJsonData();

        self::assertEquals([
            'message' => 'Пароль успешно изменен.',
        ], $data);
    }

    /**
     * Данные для проверки некорректного пароля
     *
     * @return array
     */
    public function getNotValidPasswordData(): array
    {
        return [
            'password' => '123',
        ];
    }

    /**
     * Данные для проверки успешного изменения пароля
     *
     * @return array
     */
    public function getSuccessData(): array
    {
        return [
            'password' => '123qwe',
        ];
    }

}

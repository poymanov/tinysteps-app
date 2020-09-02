<?php

declare(strict_types=1);

namespace App\Tests\Functional\Auth\Reset;

use App\Tests\Functional\DbWebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ResetTest extends DbWebTestCase
{
    private const BASE_URL = '/auth/reset';

    /**
     * Попытка GET-запроса
     */
    public function testInvalidMethod()
    {
        $this->assertInvalidMethod(Request::METHOD_GET, self::BASE_URL);
    }

    /**
     * Пароль слишком короткий
     */
    public function testShortPassword(): void
    {
        $this->assertValidationFailed(
            Request::METHOD_POST,
            self::BASE_URL . '/123',
            $this->getNotValidPasswordData(),
            ['password' => ['Значение слишком короткое. Должно быть равно 6 символам или больше.']]
        );
    }

    /**
     * Попытка установки пароля по неизвестному токену;
     */
    public function testNotExisted(): void
    {
        $this->assertBadRequest(
            Request::METHOD_POST,
            self::BASE_URL . '/1234',
            $this->getSuccessData(),
            'Неизвестный токен.'
        );
    }

    /**
     * Попытка подтверждения истекшего токена
     */
    public function testExpiredToken()
    {
        $this->assertBadRequest(
            Request::METHOD_POST,
            self::BASE_URL . '/456',
            $this->getSuccessData(),
            'Токен сброса пароля уже истек.'
        );
    }

    /**
     * Успешный сброс пароля
     */
    public function testSuccess()
    {
        $this->postWithContent(self::BASE_URL . '/123', $this->getSuccessData());

        $data = $this->getJsonData(Response::HTTP_OK);

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

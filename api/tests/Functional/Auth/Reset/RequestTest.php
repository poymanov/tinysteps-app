<?php

declare(strict_types=1);

namespace App\Tests\Functional\Auth\Reset;

use App\Tests\Functional\DbWebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RequestTest extends DbWebTestCase
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
     * Заполнен некорректный email
     */
    public function testNotValidEmail()
    {
        $this->assertValidationFailed(
            Request::METHOD_POST,
            self::BASE_URL,
            $this->getNotValidEmailData(),
            ['email' => ['Значение адреса электронной почты недопустимо.']]
        );
    }

    /**
     * Заполнен несуществующий email
     */
    public function testNotExistingEmail()
    {
        $this->assertBadRequest(
            Request::METHOD_POST,
            self::BASE_URL,
            $this->getNotExistedEmailData(),
            'Пользователь не найден.'
        );
    }

    /**
     * Email пользователя не подтверждён
     */
    public function testNotConfirmedEmail()
    {
        $this->assertBadRequest(
            Request::METHOD_POST,
            self::BASE_URL,
            $this->getNotConfirmedData(),
            'Пользователь ещё не активен.'
        );
    }

    /**
     * Сброс пароля уже запрошен
     */
    public function testAlreadyRequestedReset()
    {
        $this->assertBadRequest(
            Request::METHOD_POST,
            self::BASE_URL,
            $this->getAlreadyRequestData(),
            'Сброс пароля уже запрошен.'
        );
    }

    /**
     * Успешный запрос смены пароля
     */
    public function testSuccess()
    {
        $successData = $this->getSuccessData();

        $this->postWithContent(self::BASE_URL, $successData);

        $data = $this->getJsonData(Response::HTTP_OK);

        self::assertEquals([
            'message' => 'Проверьте ваш email.',
        ], $data);

        $this->assertIsNotInDatabase('user_users', [
            'email'               => $successData['email'],
            'reset_token_token'   => null,
            'reset_token_expires' => null,
        ]);
    }

    /**
     * Данные с некорректным email
     */
    public function getNotValidEmailData(): array
    {
        return [
            'email' => 'test',
        ];
    }

    /**
     * Данные с несуществующим email
     */
    public function getNotExistedEmailData(): array
    {
        return [
            'email' => 'not-existed-email@app.test',
        ];
    }

    /**
     * Данные ещё не подтвержденного пользователя
     *
     * @return array
     */
    public function getNotConfirmedData(): array
    {
        return [
            'email' => 'not-confirmed-confirm@app.test',
        ];
    }

    /**
     * Данные уже запрошенного сброса пароля
     *
     * @return array
     */
    public function getAlreadyRequestData(): array
    {
        return [
            'email' => 'already-requested@app.test',
        ];
    }

    /**
     * Данные с email, для которого возможен сброс email
     */
    public function getSuccessData(): array
    {
        return [
            'email' => 'existing-user@app.test',
        ];
    }

}

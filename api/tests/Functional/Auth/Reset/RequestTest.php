<?php

declare(strict_types=1);

namespace App\Tests\Functional\Auth\Reset;

use App\Tests\Functional\DbWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class RequestTest extends DbWebTestCase
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
     * Заполнен некорректный email
     */
    public function testNotValidEmail()
    {
        $this->postWithContent(self::BASE_URL, $this->getNotValidEmailData());

        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);

        $data = $this->getJsonData();

        self::assertEquals([
            'message' => 'Ошибки валидации',
            'errors'  => [
                'email' => ['Значение адреса электронной почты недопустимо.'],
            ],
        ], $data);
    }

    /**
     * Заполнен несуществующий email
     */
    public function testNotExistingEmail()
    {
        $this->postWithContent(self::BASE_URL, $this->getNotExistedEmailData());

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);

        $data = $this->getJsonData();

        self::assertEquals([
            'error' => [
                'message' => 'Пользователь не найден.',
            ],
        ], $data);
    }

    /**
     * Email пользователя не подтверждён
     */
    public function testNotConfirmedEmail()
    {
        $this->postWithContent(self::BASE_URL, $this->getNotConfirmedData());

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);

        $data = $this->getJsonData();

        self::assertEquals([
            'error' => [
                'message' => 'Пользователь ещё не активен.',
            ],
        ], $data);
    }

    /**
     * Сброс пароля уже запрошен
     */
    public function testAlreadyRequestedReset()
    {
        $this->postWithContent(self::BASE_URL, $this->getAlreadyRequestData());

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);

        $data = $this->getJsonData();

        self::assertEquals([
            'error' => [
                'message' => 'Сброс пароля уже запрошен.',
            ],
        ], $data);
    }

    /**
     * Успешный запрос смены пароля
     */
    public function testSuccess()
    {
        $successData = $this->getSuccessData();

        $this->postWithContent(self::BASE_URL, $successData);

        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        $data = $this->getJsonData();

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

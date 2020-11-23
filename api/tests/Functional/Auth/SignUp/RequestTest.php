<?php

namespace App\Tests\Functional\Auth\SignUp;

use App\Tests\Functional\DbWebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RequestTest extends DbWebTestCase
{
    private const BASE_URL = '/auth/signup';

    /**
     * Попытка GET-запроса
     */
    public function testInvalidMethod()
    {
        $this->client->request('GET', self::BASE_URL);
        self::assertResponseStatusCodeSame(Response::HTTP_METHOD_NOT_ALLOWED);
    }

    /**
     * Регистрационные данные не указаны
     */
    public function testNotValid(): void
    {
        $this->assertValidationFailed(
            Request::METHOD_POST,
            self::BASE_URL,
            [],
            [
                'first_name'            => ['Значение не должно быть пустым.'],
                'last_name'             => ['Значение не должно быть пустым.'],
                'email'                => ['Значение не должно быть пустым.'],
                'password'             => ['Значение не должно быть пустым.'],
                'password_confirmation' => ['Значение не должно быть пустым.'],
            ]
        );
    }

    /**
     * Введенные пароли должны совпадать
     */
    public function testPasswordConfirmation(): void
    {
        $this->assertValidationFailed(
            Request::METHOD_POST,
            self::BASE_URL,
            $this->getNotEqualPasswordData(),
            [
                'password_confirmation' => ['Введенные пароли должны совпадать.']
            ]
        );
    }

    /**
     * Пароль слишком короткий
     */
    public function testShortPassword(): void
    {
        $this->assertValidationFailed(
            Request::METHOD_POST,
            self::BASE_URL,
            $this->getShortPasswordData(),
            [
                'password' => ['Значение слишком короткое. Должно быть равно 6 символам или больше.']
            ]
        );
    }

    /**
     * Введен некорректный email
     */
    public function testNotValidEmail(): void
    {
        $this->assertValidationFailed(
            Request::METHOD_POST,
            self::BASE_URL,
            $this->getNotValidEmailData(),
            [
                'email' => ['Значение адреса электронной почты недопустимо.'],
            ]
        );
    }

    //

    /**
     * Email уже существует
     */
    public function testExists(): void
    {
        $this->postWithContent(self::BASE_URL, $this->getExistsData());

        $data = $this->getJsonData(Response::HTTP_BAD_REQUEST);

        self::assertEquals([
            'message' => 'Пользователь уже существует.',
            'errors' => [],
        ], $data);
    }

    /**
     * Успешная регистрация
     */
    public function testSuccess(): void
    {
        $this->postWithContent(self::BASE_URL, $this->getSuccessData());

        $data = $this->getJsonData(Response::HTTP_CREATED);

        self::assertEmpty($data);
        $this->assertIsInDatabase('user_users', [
            'email'      => 'test@test.ru',
            'name_first' => 'test',
            'name_last'  => 'test',
        ]);
    }

    /**
     * Данные для успешной регистрации
     */
    public function getSuccessData(): array
    {
        return [
            'first_name'            => 'test',
            'last_name'             => 'test',
            'email'                 => 'test@test.ru',
            'password'              => '123qwe',
            'password_confirmation' => '123qwe',
        ];
    }

    /**
     * Данные для проверки несовпадающих паролей
     *
     * @return array
     */
    public function getNotEqualPasswordData(): array
    {
        return array_merge($this->getSuccessData(), [
            'password_confirmation' => '123',
        ]);
    }

    /**
     * Данные для проверки короткого пароля
     *
     * @return array
     */
    public function getShortPasswordData(): array
    {
        return array_merge($this->getSuccessData(), [
            'password'              => '123',
            'password_confirmation' => '123',
        ]);
    }

    /**
     * Данные для проверки некорректного email
     *
     * @return array
     */
    public function getNotValidEmailData(): array
    {
        return array_merge($this->getSuccessData(), [
            'email' => 'test',
        ]);
    }

    /**
     * Данные для проверки уже существующего email
     *
     * @return array
     */
    public function getExistsData(): array
    {
        return array_merge($this->getSuccessData(), [
            'email' => 'existing-user@app.test',
        ]);
    }
}

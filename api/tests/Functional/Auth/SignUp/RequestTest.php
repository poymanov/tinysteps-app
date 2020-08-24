<?php

namespace App\Tests\Functional\Auth\SignUp;

use App\Tests\Functional\DbWebTestCase;
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
        $this->postWithContent(self::BASE_URL, []);

        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);

        $data = $this->getJsonData();

        self::assertEquals([
            'message' => 'Ошибки валидации',
            'errors'  => [
                'firstName'            => ['Значение не должно быть пустым.'],
                'lastName'             => ['Значение не должно быть пустым.'],
                'email'                => ['Значение не должно быть пустым.'],
                'password'             => ['Значение не должно быть пустым.'],
                'passwordConfirmation' => ['Значение не должно быть пустым.'],
            ],
        ], $data);
    }

    /**
     * Введенные пароли должны совпадать
     */
    public function testPasswordConfirmation(): void
    {
        $this->postWithContent(self::BASE_URL, $this->getNotEqualPasswordData());

        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);

        $data = $this->getJsonData();

        self::assertEquals([
            'message' => 'Ошибки валидации',
            'errors'  => [
                'passwordConfirmation' => ['Введенные пароли должны совпадать.'],
            ],
        ], $data);
    }

    /**
     * Пароль слишком короткий
     */
    public function testShortPassword(): void
    {
        $this->postWithContent(self::BASE_URL, $this->getShortPasswordData());

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
     * Введен некорректный email
     */
    public function testNotValidEmail(): void
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

    //

    /**
     * Email уже существует
     */
    public function testExists(): void
    {
        $this->postWithContent(self::BASE_URL, $this->getExistsData());

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);

        $data = $this->getJsonData();

        self::assertEquals([
            'error' => [
                'message' => 'Пользователь уже существует.',
            ],
        ], $data);
    }

    /**
     * Успешная регистрация
     */
    public function testSuccess(): void
    {
        $this->postWithContent(self::BASE_URL, $this->getSuccessData());

        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $data = $this->getJsonData();

        self::assertEmpty($data);
        $this->assertIsInDatabase('user_users', [
            'email' => 'test@test.ru', 'name_first' => 'test', 'name_last' => 'test'
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

<?php

declare(strict_types=1);

namespace App\Tests\Functional\Lesson\Teacher\Create;

use App\DataFixtures\UserFixture as CommomUserFixture;
use App\Tests\Fixtures\UserFixture;
use App\Tests\Functional\DbWebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CreateTest extends DbWebTestCase
{
    private const BASE_URL = '/teachers/create';

    private const BASE_METHOD = Request::METHOD_POST;

    /**
     * Попытка GET-запроса
     */
    public function testInvalidMethod(): void
    {
        $this->assertInvalidMethod(Request::METHOD_GET, self::BASE_URL);
    }

    /**
     * Попытка создания цели не авторизованным пользователем
     */
    public function testNotAuth(): void
    {
        $this->assertNotAuth(self::BASE_METHOD, self::BASE_URL);
    }

    /**
     * Попытка создания цели пользователем без роли ROLE_ADMIN
     */
    public function testNotAdmin(): void
    {
        $this->assertNotAdmin(self::BASE_METHOD, self::BASE_URL);
    }

    /**
     * Обязательные поля не заполнены
     */
    public function testEmpty(): void
    {
        $this->assertValidationFailed(
            self::BASE_METHOD,
            self::BASE_URL,
            [],
            [
                'userId'      => ['Значение не должно быть пустым.'],
                'description' => ['Значение не должно быть пустым.'],
                'price'       => ['Значение не должно быть пустым.'],
            ]
        );
    }

    /**
     * ID пользователя указан в неправильном формате
     */
    public function testNotValidUserId(): void
    {
        $this->assertValidationFailed(
            self::BASE_METHOD,
            self::BASE_URL,
            $this->getNotValidUserIdData(),
            [
                'userId' => ['Значение не соответствует формату UUID.'],
            ]
        );
    }

    /**
     * Цена указана в неправильном формате
     */
    public function testNotValidPrice(): void
    {
        $this->assertBadRequest(
            self::BASE_METHOD,
            self::BASE_URL,
            $this->getNotValidPriceData(),
            'Неверный тип одного/нескольких указанных полей'
        );
    }

    /**
     * Цена 0
     */
    public function testZeroPrice(): void
    {
        $this->assertValidationFailed(
            self::BASE_METHOD,
            self::BASE_URL,
            $this->getZeroPriceData(),
            [
                'price' => ['Значение должно быть положительным.'],
            ]
        );
    }

    /**
     * Цена меньше 0
     */
    public function testLessThanZeroPrice(): void
    {
        $this->assertValidationFailed(
            self::BASE_METHOD,
            self::BASE_URL,
            $this->getLessThanZeroPriceData(),
            [
                'price' => ['Значение должно быть положительным.'],
            ]
        );
    }

    /**
     * Описание меньше 150 символов
     */
    public function testTooShortDescription(): void
    {
        $this->assertValidationFailed(
            self::BASE_METHOD,
            self::BASE_URL,
            $this->getTooShortDescriptionData(),
            [
                'description' => ['Значение слишком короткое. Должно быть равно 150 символам или больше.'],
            ]
        );
    }

    /**
     * Указанный пользователь не существует
     */
    public function testNotExistedUser(): void
    {
        $this->assertBadRequest(
            self::BASE_METHOD,
            self::BASE_URL,
            $this->getNotExistedUserData(),
            'Пользователь не найден.'
        );
    }

    /**
     * Пользователь не находится в активном состоянии
     */
    public function testNotActiveUser(): void
    {
        $this->assertBadRequest(
            self::BASE_METHOD,
            self::BASE_URL,
            $this->getNotActiveUserData(),
            'Пользователь не находится в активном состоянии.'
        );
    }

    /**
     * Пользователю не назначена роль ROLE_USER
     */
    public function testNotRoleUser(): void
    {
        $this->assertBadRequest(
            self::BASE_METHOD,
            self::BASE_URL,
            $this->getNotRoleUserData(),
            'Роль пользователя не подходит для назначения в преподаватели.'
        );
    }

    /**
     * Пользователь уже назначен преподавателем
     */
    public function testUserWithExistingUser(): void
    {
        $this->assertBadRequest(
            self::BASE_METHOD,
            self::BASE_URL,
            $this->getUserWithExistedUserData(),
            'Пользователь уже назначен преподавателем.'
        );
    }

    /**
     * Успешное создание преподавателя
     */
    public function testSuccess(): void
    {
        $this->authAsAdmin();

        $this->postWithContent(self::BASE_URL, $this->getSuccessData());

        $data = $this->getJsonData(Response::HTTP_CREATED);

        self::assertEmpty($data);
        $this->assertIsInDatabase('lesson_teachers', [
            'alias'   => 'first-last',
            'user_id' => CommomUserFixture::USER_1_ID,
            'status'  => 'active',
            'price'   => 100,
            'rating'  => 0,
        ]);
    }


    /**
     * Данные с userId в неправильном формате
     *
     * @return array
     */
    private function getNotValidUserIdData(): array
    {
        return array_merge($this->getSuccessData(), [
            'user_id' => '123',
        ]);
    }

    /**
     * Данные с ценой в неправильном формате
     *
     * @return array
     */
    private function getNotValidPriceData(): array
    {
        return array_merge($this->getSuccessData(), [
            'price' => 'test',
        ]);
    }

    /**
     * Данные с нулевой ценой
     *
     * @return array
     */
    private function getZeroPriceData(): array
    {
        return array_merge($this->getSuccessData(), [
            'price' => 0,
        ]);
    }

    /**
     * Данные с ценой меньше нуля
     *
     * @return array
     */
    private function getLessThanZeroPriceData(): array
    {
        return array_merge($this->getSuccessData(), [
            'price' => -1,
        ]);
    }

    /**
     * Данные с коротким описаем
     *
     * @return array
     */
    private function getTooShortDescriptionData(): array
    {
        return array_merge($this->getSuccessData(), [
            'description' => 'test',
        ]);
    }

    /**
     * Данные с несуществующим пользователем
     *
     * @return array
     */
    private function getNotExistedUserData(): array
    {
        return array_merge($this->getSuccessData(), [
            'user_id' => '00000000-0000-0000-0000-000000000099',
        ]);
    }

    /**
     * Данные пользователя, не подтвердившего свою учетную запись
     *
     * @return array
     */
    private function getNotActiveUserData(): array
    {
        return array_merge($this->getSuccessData(), [
            'user_id' => UserFixture::NOT_CONFIRMED_UUID,
        ]);
    }

    /**
     * Данные пользователя, не обладающего ролью ROLE_USER
     *
     * @return array
     */
    private function getNotRoleUserData(): array
    {
        return array_merge($this->getSuccessData(), [
            'user_id' => CommomUserFixture::USER_2_ID,
        ]);
    }

    /**
     * Данные пользователя, который уже назначен преподавателем
     *
     * @return array
     */
    private function getUserWithExistedUserData(): array
    {
        return array_merge($this->getSuccessData(), [
            'user_id' => UserFixture::EXISTING_UUID,
        ]);
    }

    /**
     * Данные для успешного создания
     *
     * @return array
     */
    private function getSuccessData(): array
    {
        return [
            'user_id'     => CommomUserFixture::USER_1_ID,
            'price'       => 100,
            'description' => $this->getRandomString(),
        ];
    }
}

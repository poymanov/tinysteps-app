<?php

declare(strict_types=1);

namespace App\Tests\Functional\Lesson\Teacher\Update;

use App\Tests\Fixtures\TeacherFixture;
use App\Tests\Functional\DbWebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PriceTest extends DbWebTestCase
{
    private const BASE_URL = '/teachers/update/price/';

    private const BASE_URL_TEACHER_1 = self::BASE_URL . TeacherFixture::TEACHER_1_ID;

    private const BASE_URL_TEACHER_2 = self::BASE_URL . TeacherFixture::TEACHER_2_ID;

    private const BASE_METHOD = Request::METHOD_PATCH;

    /**
     * Попытка GET-запроса
     */
    public function testInvalidMethod(): void
    {
        $this->assertInvalidMethod(Request::METHOD_GET, self::BASE_URL_TEACHER_1);
    }

    /**
     * Попытка выполнения запроса без аутентификации
     */
    public function testNotAuth(): void
    {
        $this->assertNotAuth(self::BASE_METHOD, self::BASE_URL_TEACHER_1);
    }

    /**
     * Попытка выполнения запроса пользователем без прав администратора
     */
    public function testNotAdmin(): void
    {
        $this->assertNotAdmin(self::BASE_METHOD, self::BASE_URL_TEACHER_1);
    }

    /**
     * Изменение стоимости услуг для преподавателя c uuid в неправильном формате
     */
    public function testNotValidUuid(): void
    {
        $this->assertNotValidUuid(self::BASE_METHOD, self::BASE_URL . '123');
    }

    /**
     * Попытка изменения описания несуществующего преподавателя
     */
    public function testNotFound(): void
    {
        $this->assertNotFound(self::BASE_METHOD, self::BASE_URL . '00000000-0000-0000-0000-000000000099');
    }

    /**
     * Значение новой стоимости услуг не указано
     */
    public function testEmpty(): void
    {
        $this->assertValidationFailed(
            self::BASE_METHOD,
            self::BASE_URL_TEACHER_1,
            [],
            ['price' => ['Значение не должно быть пустым.']]
        );
    }

    /**
     * Стоимость услуг указана в неправильном формате
     */
    public function testNotValidPrice(): void
    {
        $this->assertBadRequest(
            self::BASE_METHOD,
            self::BASE_URL_TEACHER_1,
            $this->getNotValidPriceData(),
            'Неверный тип одного/нескольких указанных полей'
        );
    }

    /**
     * Стоимость услуг равна 0
     */
    public function testZeroPrice(): void
    {
        $this->assertValidationFailed(
            self::BASE_METHOD,
            self::BASE_URL_TEACHER_1,
            $this->getZeroPriceData(),
            [
                'price' => ['Значение должно быть положительным.'],
            ]
        );
    }

    /**
     * Стоимость услуг меньше 0
     */
    public function testLessThanZeroPrice(): void
    {
        $this->assertValidationFailed(
            self::BASE_METHOD,
            self::BASE_URL_TEACHER_1,
            $this->getLessThanZeroPriceData(),
            [
                'price' => ['Значение должно быть положительным.'],
            ]
        );
    }

    /**
     * Попытка изменения стоимости услуг для преподавателя, находящегося в архивном состоянии
     */
    public function testTeacherArchived(): void
    {
        $this->assertBadRequest(
            self::BASE_METHOD,
            self::BASE_URL_TEACHER_2,
            $this->getSuccessData(),
            'Преподаватель находится в архиве и недоступен для изменений.'
        );
    }

    /**
     * Успешное изменение стоимости услуг
     */
    public function testSuccess(): void
    {
        $this->authAsAdmin();

        $this->patchWithContent(self::BASE_URL_TEACHER_1, $this->getSuccessData());

        $data = $this->getJsonData(Response::HTTP_OK);

        self::assertEmpty($data);

        $this->assertIsInDatabase('lesson_teachers', [
            'id'    => TeacherFixture::TEACHER_1_ID,
            'price' => 100,
        ]);
    }

    /**
     * Данные с ценой в неправильном формате
     *
     * @return array
     */
    private function getNotValidPriceData(): array
    {
        return [
            'price' => 'test',
        ];
    }

    /**
     * Данные с нулевой ценой
     *
     * @return array
     */
    private function getZeroPriceData(): array
    {
        return [
            'price' => 0,
        ];
    }

    /**
     * Данные с ценой меньше нуля
     *
     * @return array
     */
    private function getLessThanZeroPriceData(): array
    {
        return [
            'price' => -1,
        ];
    }

    /**
     * Данные для успешного создания
     *
     * @return array
     */
    private function getSuccessData(): array
    {
        return [
            'price' => 100,
        ];
    }
}

<?php

declare(strict_types=1);

namespace App\Tests\Functional\Lesson\Teacher\Update;

use App\Tests\Fixtures\TeacherFixture;
use App\Tests\Functional\DbWebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RatingTest extends DbWebTestCase
{
    private const BASE_URL = '/teachers/update/rating/';

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
     * Изменение рейтинга преподавателя c uuid в неправильном формате
     */
    public function testNotValidUuid(): void
    {
        $this->assertNotValidUuid(self::BASE_METHOD, self::BASE_URL . '123');
    }

    /**
     * Попытка изменения рейтинга несуществующего преподавателя
     */
    public function testNotFound(): void
    {
        $this->assertNotFound(self::BASE_METHOD, self::BASE_URL . '00000000-0000-0000-0000-000000000099');
    }

    /**
     * Значение нового рейтинга не указано
     */
    public function testEmpty(): void
    {
        $this->assertValidationFailed(
            self::BASE_METHOD,
            self::BASE_URL_TEACHER_1,
            [],
            ['rating' => ['Значение не должно быть пустым.']]
        );
    }

    /**
     * Рейтинг указан в неправильном формате
     */
    public function testNotValidRating(): void
    {
        $this->assertBadRequest(
            self::BASE_METHOD,
            self::BASE_URL_TEACHER_1,
            $this->getNotValidRatingData(),
            'Неверный тип одного/нескольких указанных полей'
        );
    }

    /**
     * Данные с ценой в неправильном формате
     *
     * @return array
     */
    private function getNotValidRatingData(): array
    {
        return [
            'rating' => 'test',
        ];
    }

    /**
     * Рейтинг меньше 0
     */
    public function testLessThanZero(): void
    {
        $this->assertValidationFailed(
            self::BASE_METHOD,
            self::BASE_URL_TEACHER_1,
            $this->getLessThanZeroData(),
            [
                'rating' => ['Значение должно быть положительным или равным нулю.'],
            ]
        );
    }

    /**
     * Рейтинг больше максимально допустимого значения
     */
    public function testGreaterThanMax(): void
    {
        $this->assertValidationFailed(
            self::BASE_METHOD,
            self::BASE_URL_TEACHER_1,
            $this->getGreaterThanMaxData(),
            [
                'rating' => ['Значение должно быть меньше или равно "5".'],
            ]
        );
    }

    /**
     * Попытка изменения рейтинга преподавателя, находящегося в архивном состоянии
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
     * Успешное изменение рейтинга
     */
    public function testSuccess(): void
    {
        $this->authAsAdmin();

        $this->patchWithContent(self::BASE_URL_TEACHER_1, $this->getSuccessData());

        $data = $this->getJsonData(Response::HTTP_OK);

        self::assertEmpty($data);

        $this->assertIsInDatabase('lesson_teachers', [
            'id'    => TeacherFixture::TEACHER_1_ID,
            'rating' => 4,
        ]);
    }

    /**
     * Рейтинг 0
     */
    public function testZero(): void
    {
        $this->authAsAdmin();

        $this->patchWithContent(self::BASE_URL_TEACHER_1, $this->getZeroData());

        $data = $this->getJsonData(Response::HTTP_OK);

        self::assertEmpty($data);

        $this->assertIsInDatabase('lesson_teachers', [
            'id'    => TeacherFixture::TEACHER_1_ID,
            'rating' => 0,
        ]);
    }

    /**
     * Данные с рейтингом меньше нуля
     *
     * @return array
     */
    private function getLessThanZeroData(): array
    {
        return [
            'rating' => -1,
        ];
    }

    /**
     * Данные с нулевым рейтингом
     *
     * @return array
     */
    private function getZeroData(): array
    {
        return [
            'rating' => 0,
        ];
    }

    /**
     * Данные с рейтингом больше допустимого значения
     *
     * @return array
     */
    private function getGreaterThanMaxData(): array
    {
        return [
            'rating' => 100,
        ];
    }

    /**
     * Данные для успешного изменения
     *
     * @return array
     */
    private function getSuccessData(): array
    {
        return [
            'rating' => 4,
        ];
    }
}

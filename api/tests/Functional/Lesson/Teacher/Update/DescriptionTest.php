<?php

declare(strict_types=1);

namespace App\Tests\Functional\Lesson\Teacher\Update;

use App\Tests\Fixtures\TeacherFixture;
use App\Tests\Functional\DbWebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DescriptionTest extends DbWebTestCase
{
    private const BASE_URL = '/teachers/update/description/';

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
     * Изменение описания для преподавателя c uuid в неправильном формате
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
     * Значение нового описания не указано
     */
    public function testEmpty(): void
    {
        $this->assertValidationFailed(
            self::BASE_METHOD,
            self::BASE_URL_TEACHER_1,
            [],
            ['description' => ['Значение не должно быть пустым.']]
        );
    }

    /**
     * Описание меньше 150 символов
     */
    public function testTooShortDescription(): void
    {
        $this->assertValidationFailed(
            self::BASE_METHOD,
            self::BASE_URL_TEACHER_1,
            $this->getTooShortDescriptionData(),
            [
                'description' => ['Значение слишком короткое. Должно быть равно 150 символам или больше.'],
            ]
        );
    }

    /**
     * Попытка изменения описания для преподавателя, находящегося в архивном состоянии
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
     * Успешное изменение alias
     */
    public function testSuccess(): void
    {
        $this->authAsAdmin();

        $successData = $this->getSuccessData();

        $this->patchWithContent(self::BASE_URL_TEACHER_1, $successData);

        $data = $this->getJsonData(Response::HTTP_OK);

        self::assertEmpty($data);

        $this->assertIsInDatabase('lesson_teachers', [
            'id'    => TeacherFixture::TEACHER_1_ID,
            'description' => $successData['description'],
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
     * Данные для успешного создания
     *
     * @return array
     */
    private function getSuccessData(): array
    {
        return [
            'description' => $this->getRandomString(),
        ];
    }
}

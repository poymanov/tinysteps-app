<?php

declare(strict_types=1);

namespace App\Tests\Functional\Lesson\Teacher\Schedule;

use App\Tests\Fixtures\ScheduleFixture;
use App\Tests\Fixtures\TeacherFixture;
use App\Tests\Functional\DbWebTestCase;
use Symfony\Component\HttpFoundation\Request;

class DeleteTest extends DbWebTestCase
{
    private const BASE_URL = '/teachers/schedule/remove/';

    private const BASE_URL_TEACHER_1 = self::BASE_URL . TeacherFixture::TEACHER_1_ID;

    private const BASE_URL_TEACHER_2 = self::BASE_URL . TeacherFixture::TEACHER_2_ID;

    private const BASE_URL_TEACHER_3 = self::BASE_URL . TeacherFixture::TEACHER_3_ID;

    private const BASE_METHOD = Request::METHOD_DELETE;

    /**
     * Попытка GET-запроса
     */
    public function testInvalidMethod(): void
    {
        $this->assertInvalidMethod(Request::METHOD_GET, self::BASE_URL_TEACHER_1);
    }

    /**
     * Попытка удаления графика не авторизованным пользователем
     */
    public function testNotAuth(): void
    {
        $this->assertNotAuth(self::BASE_METHOD, self::BASE_URL_TEACHER_1);
    }

    /**
     * Попытка удаления графика пользователем без роли ROLE_ADMIN
     */
    public function testNotAdmin(): void
    {
        $this->assertNotAdmin(self::BASE_METHOD, self::BASE_URL_TEACHER_1);
    }

    /**
     * ID преподавателя указан в неправильном формате
     */
    public function testNotValidTeacherUuid(): void
    {
        $this->assertNotValidUuid(self::BASE_METHOD, self::BASE_URL . '123');
    }

    /**
     * Указанный преподаватель не существует
     */
    public function testTeacherNotFound(): void
    {
        $this->assertNotFound(self::BASE_METHOD, self::BASE_URL . '00000000-0000-0000-0000-000000000099');
    }

    /**
     * ID графика не указан
     */
    public function testEmptyId(): void
    {
        $this->assertValidationFailed(
            self::BASE_METHOD,
            self::BASE_URL_TEACHER_1,
            [],
            ['id' => ['Значение не должно быть пустым.']]
        );
    }

    /**
     * ID графика указан в неправильном формате
     */
    public function testNotValidId(): void
    {
        $this->assertValidationFailed(
            self::BASE_METHOD,
            self::BASE_URL_TEACHER_1,
            $this->getNotValidIdData(),
            ['id' => ['Значение не соответствует формату UUID.']]
        );
    }

    /**
     * Указанный график не существует
     */
    public function testNotExistedSchedule(): void
    {
        $this->assertBadRequest(
            self::BASE_METHOD,
            self::BASE_URL_TEACHER_1,
            $this->getNotExistedScheduleData(),
            'График преподавателя не найден.'
        );
    }

    /**
     * Указанный график не относится к указанному преподавателю
     */
    public function testWrongScheduleTeacher(): void
    {
        $this->assertBadRequest(
            self::BASE_METHOD,
            self::BASE_URL_TEACHER_3,
            $this->getSuccessData(),
            'График не относится к преподавателю.'
        );
    }

    /**
     * Попытка удаления графика для преподавателя, находящегося в архивном состоянии
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
     * Успешное удаление цели обучения у преподавателя
     */
    public function testSuccess(): void
    {
        $this->authAsAdmin();

        $this->deleteWithContent(self::BASE_URL_TEACHER_1, $this->getSuccessData());

        self::assertEmpty($this->client->getResponse()->getContent());

        $this->assertIsNotInDatabase('lesson_schedules', [
            'id'         => ScheduleFixture::ID_1,
            'teacher_id' => TeacherFixture::TEACHER_1_ID,
        ]);
    }

    /**
     * Данные для графика с датой в неправильном формате
     *
     * @return string[]
     */
    public function getNotValidIdData(): array
    {
        return [
            'id' => '123',
        ];
    }

    /**
     * Данные для несуществующего графика
     *
     * @return string[]
     */
    public function getNotExistedScheduleData(): array
    {
        return [
            'id' => '00000000-0000-0000-0000-000000000099',
        ];
    }

    /**
     * Данные для успешного удаления графика
     *
     * @return string[]
     */
    public function getSuccessData(): array
    {
        return [
            'id' => ScheduleFixture::ID_1,
        ];
    }
}

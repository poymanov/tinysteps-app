<?php

declare(strict_types=1);

namespace App\Tests\Functional\Lesson\Teacher\Schedule;

use App\Tests\Fixtures\TeacherFixture;
use App\Tests\Functional\DbWebTestCase;
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AddTest extends DbWebTestCase
{
    private const BASE_URL = '/teachers/schedule/add/';

    private const BASE_URL_TEACHER_1 = self::BASE_URL . TeacherFixture::TEACHER_1_ID;

    private const BASE_URL_TEACHER_2 = self::BASE_URL . TeacherFixture::TEACHER_2_ID;

    private const BASE_METHOD = Request::METHOD_POST;

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
     * Попытка создания графика пользователем без роли ROLE_ADMIN;
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
     * Дата/время графика не указаны
     */
    public function testEmptyDate(): void
    {
        $this->assertValidationFailed(
            self::BASE_METHOD,
            self::BASE_URL_TEACHER_1,
            [],
            ['date' => ['Значение не должно быть пустым.']]
        );
    }

    /**
     * Дата/время графика указаны в неправильном формате
     */
    public function testNotValidDate(): void
    {
        $this->assertValidationFailed(
            self::BASE_METHOD,
            self::BASE_URL_TEACHER_1,
            $this->getNotValidDateData(),
            ['date' => ['Значение даты и времени недопустимо.']]
        );
    }

    /**
     * Попытка добавления графика для преподавателя, находящегося в архивном состоянии
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
     * Преподавателю уже добавлен график на данную дату и время
     */
    public function testDateAdded(): void
    {
        $this->assertBadRequest(
            self::BASE_METHOD,
            self::BASE_URL_TEACHER_1,
            $this->getScheduleAddedData(),
            'График уже добавлен преподавателю.'
        );
    }

    /**
     * Попытка добавления графика "задним числом"
     */
    public function testDateInPast(): void
    {
        $this->assertBadRequest(
            self::BASE_METHOD,
            self::BASE_URL_TEACHER_1,
            $this->getScheduleDateInPastData(),
            'Попытка добавления графика "задним числом".'
        );
    }

    /**
     * Успешное добавление графика преподавателю
     */
    public function testSuccess(): void
    {
        $this->authAsAdmin();

        $successData = $this->getSuccessData();

        $this->postWithContent(self::BASE_URL_TEACHER_1, $successData);

        $data = $this->getJsonData(Response::HTTP_CREATED);

        self::assertEmpty($data);

        $this->assertIsInDatabase('lesson_schedules', [
            'teacher_id' => TeacherFixture::TEACHER_1_ID,
            'date'    => $successData['date'],
        ]);
    }

    /**
     * Данные для графика с датой в неправильном формате
     *
     * @return string[]
     */
    public function getNotValidDateData(): array
    {
        return [
            'date' => '123',
        ];
    }

    /**
     * Данные для графика, который добавляется "задним числом"
     *
     * @return array
     */
    public function getScheduleDateInPastData(): array
    {
        $date = new DateTimeImmutable();
        $date = $date->modify('-1 year');

        return [
            'date' => $date->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Данные для графика, который уже добавлен преподавателю
     *
     * @return array
     */
    public function getScheduleAddedData(): array
    {
        $date = new DateTimeImmutable();
        $date = $date->modify('+1 year');
        $date = $date->setTime(12, 15, 0);

        return [
            'date' => $date->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Данные для успешного добавления графику преподавателю
     *
     * @return array
     */
    public function getSuccessData(): array
    {
        return [
            'date' => (new DateTimeImmutable('2030-11-11 23:53:00'))->format('Y-m-d H:i:s'),
        ];
    }
}

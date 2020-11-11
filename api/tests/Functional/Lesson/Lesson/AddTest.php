<?php

declare(strict_types=1);

namespace App\Tests\Functional\Lesson\Lesson;

use App\DataFixtures\UserFixture as CommonUserFixture;
use App\Tests\Fixtures\ScheduleFixture;
use App\Tests\Fixtures\UserFixture;
use App\Tests\Functional\DbWebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AddTest extends DbWebTestCase
{
    private const BASE_URL = '/lessons/add';

    private const BASE_METHOD = Request::METHOD_POST;

    /**
     * Попытка GET-запроса
     */
    public function testInvalidMethod(): void
    {
        $this->assertInvalidMethod(Request::METHOD_GET, self::BASE_URL);
    }

    /**
     * Попытка записи на урок неавторизованным пользователем
     */
    public function testNotAuth(): void
    {
        $this->assertNotAuth(self::BASE_METHOD, self::BASE_URL);
    }

    /**
     * График урока не указан
     */
    public function testEmptySchedule(): void
    {
        $this->assertValidationFailed(
            self::BASE_METHOD,
            self::BASE_URL,
            $this->getEmptyScheduleData(),
            ['schedule_id' => ['Значение не должно быть пустым.']]
        );
    }

    /**
     * ID графика указан в неправильном формате
     */
    public function testNotValidScheduleUuid(): void
    {
        $this->assertValidationFailed(
            self::BASE_METHOD,
            self::BASE_URL,
            $this->getNotValidScheduleIdData(),
            ['schedule_id' => ['Значение не соответствует формату UUID.']]
        );
    }

    /**
     * Попытка записи на урок пользователем с неподтвержденной учетной записью
     */
    public function testNotConfirmedUserEmail(): void
    {
        $this->auth([
            'email'    => 'not-confirmed-confirm@app.test',
            'password' => '123qwe',
        ]);

        $this->requestWithContent(self::BASE_METHOD, self::BASE_URL, $this->getSuccessData());

        $data = $this->getJsonData(Response::HTTP_BAD_REQUEST);

        self::assertEquals([
            'error' => [
                'message' => 'Пользователь не активен.',
            ],
        ], $data);
    }

    /**
     * Указанный график не существует
     */
    public function testNotExistedSchedule(): void
    {
        $this->assertBadRequest(
            self::BASE_METHOD,
            self::BASE_URL,
            $this->getNotExistedScheduleData(),
            'График преподавателя не найден.'
        );
    }

    /**
     * На указанный урок уже была произведена запись
     */
    public function testLessonAlreadyExists(): void
    {
        $this->assertBadRequest(
            self::BASE_METHOD,
            self::BASE_URL,
            $this->getAlreadyExistsLessonData(),
            'Запись на урок уже существует.'
        );
    }

    /**
     * Попытка записи на урок из "прошлого"
     */
    public function testLessonAtPast(): void
    {
        $this->assertBadRequest(
            self::BASE_METHOD,
            self::BASE_URL,
            $this->getLessonAtPastData(),
            'Попытка записи на урок из прошедшего периода.'
        );
    }

    /**
     * Успешная запись на урок
     */
    public function testSuccess(): void
    {
        $this->authAsAdmin();

        $successData = $this->getSuccessData();

        $this->postWithContent(self::BASE_URL, $successData);

        $data = $this->getJsonData(Response::HTTP_CREATED);

        self::assertEmpty($data);

        $this->assertIsInDatabase('lesson_lessons', [
            'user_id'     => CommonUserFixture::USER_2_ID,
            'schedule_id' => $successData['schedule_id'],
        ]);
    }

    /**
     * Данные без указания графика занятия
     *
     * @return array
     */
    public function getEmptyScheduleData(): array
    {
        return [
            'userId' => UserFixture::EXISTING_UUID,
        ];
    }

    /**
     * Данные без указания графика занятия
     *
     * @return array
     */
    public function getNotValidScheduleIdData(): array
    {
        return [
            'schedule_id' => '123',
        ];
    }

    /**
     * Данные для успешной записи на урок
     *
     * @return string[]
     */
    public function getNotExistedScheduleData(): array
    {
        return [
            'schedule_id' => '00000000-0000-0000-0000-000000000099',
        ];
    }

    /**
     * Данные для уже существующей записи
     *
     * @return string[]
     */
    public function getAlreadyExistsLessonData(): array
    {
        return [
            'schedule_id' => ScheduleFixture::ID_2,
        ];
    }

    /**
     * Данные для урока из "прошлого"
     *
     * @return string[]
     */
    public function getLessonAtPastData(): array
    {
        return [
            'schedule_id' => ScheduleFixture::ID_3,
        ];
    }

    /**
     * Данные для успешной записи на урок
     *
     * @return string[]
     */
    public function getSuccessData(): array
    {
        return [
            'schedule_id' => ScheduleFixture::ID_1,
        ];
    }
}

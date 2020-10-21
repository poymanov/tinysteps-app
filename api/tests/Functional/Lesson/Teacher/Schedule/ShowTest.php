<?php

declare(strict_types=1);

namespace App\Tests\Functional\Lesson\Teacher\Schedule;

use App\Tests\Fixtures\ScheduleFixture;
use App\Tests\Fixtures\TeacherFixture;
use App\Tests\Functional\DbWebTestCase;
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ShowTest extends DbWebTestCase
{
    private const BASE_URL = '/teachers/schedule/show/all/';

    private const BASE_URL_TEACHER_1 = self::BASE_URL . TeacherFixture::TEACHER_1_ID;

    private const BASE_URL_TEACHER_2 = self::BASE_URL . TeacherFixture::TEACHER_2_ID;

    private const BASE_URL_TEACHER_3 = self::BASE_URL . TeacherFixture::TEACHER_3_ID;

    private const BASE_METHOD = Request::METHOD_GET;

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
     * Попытка просмотра графика для преподавателя, находящегося в архивном состоянии
     */
    public function testTeacherArchived(): void
    {
        $this->assertNotFound(self::BASE_METHOD, self::BASE_URL_TEACHER_2);
    }

    /**
     * Успешное получение графика занятий преподавателя
     */
    public function testSuccess()
    {
        $this->client->request(self::BASE_METHOD, self::BASE_URL_TEACHER_1);

        $data = $this->getJsonData(Response::HTTP_OK);

        $date = new DateTimeImmutable('2030-12-12 12:15:00');

        self::assertEquals([
            [
                'id'   => ScheduleFixture::ID_1,
                'date' => $date->format('Y-m-d H:i:s'),
            ],
        ], $data);
    }

    /**
     * Получение пустого списка графика занятий преподавателя
     */
    public function testEmpty()
    {
        $this->client->request(self::BASE_METHOD, self::BASE_URL_TEACHER_3);

        $data = $this->getJsonData(Response::HTTP_OK);

        self::assertEmpty($data);
    }
}

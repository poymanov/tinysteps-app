<?php

declare(strict_types=1);

namespace App\Tests\Functional\Lesson\Teacher\Goal;

use App\DataFixtures\GoalFixture;
use App\Tests\Fixtures\TeacherFixture;
use App\Tests\Functional\DbWebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ShowTest extends DbWebTestCase
{
    private const BASE_URL = '/teachers/goal/show/all/';

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
     * Попытка просмотра целей для преподавателя, находящегося в архивном состоянии
     */
    public function testTeacherArchived(): void
    {
        $this->assertNotFound(self::BASE_METHOD, self::BASE_URL_TEACHER_2);
    }

    /**
     * Успешное получение списка целей обучения преподавателя
     */
    public function testSuccess()
    {
        $this->client->request(self::BASE_METHOD, self::BASE_URL_TEACHER_1);

        $data = $this->getJsonData(Response::HTTP_OK);

        self::assertEquals([
            [
                'id'         => GoalFixture::GOAL_2_ID,
                'alias'      => 'dla-uceby',
                'name'       => 'Для учебы',
                'status'     => 'active',
                'sort'       => 2,
                'icon'       => '🏫',
                'created_at' => '2020-01-02 10:00:00',
            ],
        ], $data);
    }

    /**
     * Успешное получение списка целей обучения преподавателя
     */
    public function testEmpty()
    {
        $this->client->request(self::BASE_METHOD, self::BASE_URL_TEACHER_3);

        $data = $this->getJsonData(Response::HTTP_OK);

        self::assertEmpty($data);
    }
}

<?php

declare(strict_types=1);

namespace App\Tests\Functional\Lesson\Teacher\Show;

use App\Tests\Fixtures\TeacherFixture;
use App\Tests\Fixtures\UserFixture;
use App\Tests\Functional\DbWebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class OneTest extends DbWebTestCase
{
    private const BASE_URL = '/teachers/show/one/';

    private const BASE_URL_TEACHER_1 = self::BASE_URL . TeacherFixture::TEACHER_1_ID;

    private const BASE_METHOD = Request::METHOD_GET;

    /**
     * Запрос преподавателя c uuid в неправильном формате
     */
    public function testNotValidUuid(): void
    {
        $this->assertNotValidUuid(self::BASE_METHOD, self::BASE_URL . '123');
    }

    /**
     * Запрос несуществующего преподавателя
     */
    public function testNotFound(): void
    {
        $this->assertNotFound(self::BASE_METHOD, self::BASE_URL . '00000000-0000-0000-0000-000000000099');
    }

    /**
     * Успешный запрос преподавателя
     */
    public function testSuccess(): void
    {
        $this->authAsAdmin();

        $this->client->request(self::BASE_METHOD, self::BASE_URL_TEACHER_1);

        $data = $this->getJsonData(Response::HTTP_OK);

        self::assertEquals([
            'id'          => TeacherFixture::TEACHER_1_ID,
            'user_id'     => UserFixture::EXISTING_UUID,
            'alias'       => 'existing-user',
            'description' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient.',
            'price'       => 100,
            'rating'      => 0.0,
            'status'      => 'active',
            'created_at'  => '2020-01-01 15:00:00',
        ], $data);
    }
}

<?php

declare(strict_types=1);

namespace App\Tests\Functional\Lesson\Teacher\Show;

use App\Tests\Fixtures\TeacherFixture;
use App\Tests\Fixtures\UserFixture;
use App\Tests\Functional\DbWebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ListTest extends DbWebTestCase
{
    private const BASE_URL = '/teachers/show/';

    private const BASE_METHOD = Request::METHOD_GET;

    /**
     * Получение списка всех преподавателей, отсортированных по возрастанию идентификаторов
     */
    public function testAll(): void
    {
        $this->client->request(self::BASE_METHOD, self::BASE_URL . 'all');

        $data = $this->getJsonData(Response::HTTP_OK);

        self::assertEquals([
            [
                'id'          => TeacherFixture::TEACHER_1_ID,
                'user_id'     => UserFixture::EXISTING_UUID,
                'alias'       => 'existing-user',
                'description' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient.',
                'price'       => 100,
                'rating'      => 0.0,
                'status'      => 'active',
                'created_at'  => '2020-01-01 15:00:00',
            ],
            [
                'id'          => TeacherFixture::TEACHER_2_ID,
                'user_id'     => UserFixture::ALREADY_REQUESTED_UUID,
                'alias'       => 'already-request-user',
                'description' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient.',
                'price'       => 100,
                'rating'      => 0.0,
                'status'      => 'archived',
                'created_at'  => '2020-01-01 16:00:00',
            ],
        ], $data);
    }

    /**
     * Получение списка активных преподавателей, отсортированных по возрастанию идентификаторов
     */
    public function testActive(): void
    {
        $this->client->request(self::BASE_METHOD, self::BASE_URL . 'active');

        $data = $this->getJsonData(Response::HTTP_OK);

        self::assertEquals([
            [
                'id'          => TeacherFixture::TEACHER_1_ID,
                'user_id'     => UserFixture::EXISTING_UUID,
                'alias'       => 'existing-user',
                'description' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient.',
                'price'       => 100,
                'rating'      => 0.0,
                'status'      => 'active',
                'created_at'  => '2020-01-01 15:00:00',
            ],
        ], $data);
    }

    /**
     * Получение списка архивных преподавателей, отсортированных по возрастанию идентификаторов
     */
    public function testArchived(): void
    {
        $this->client->request(self::BASE_METHOD, self::BASE_URL . 'archived');

        $data = $this->getJsonData(Response::HTTP_OK);

        self::assertEquals([
            [
                'id'          => TeacherFixture::TEACHER_2_ID,
                'user_id'     => UserFixture::ALREADY_REQUESTED_UUID,
                'alias'       => 'already-request-user',
                'description' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient.',
                'price'       => 100,
                'rating'      => 0.0,
                'status'      => 'archived',
                'created_at'  => '2020-01-01 16:00:00',
            ],
        ], $data);
    }
}

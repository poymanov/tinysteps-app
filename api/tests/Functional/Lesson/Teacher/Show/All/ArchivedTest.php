<?php

declare(strict_types=1);

namespace App\Tests\Functional\Lesson\Teacher\Show\All;

use App\Tests\Fixtures\TeacherFixture;
use App\Tests\Fixtures\UserFixture;
use App\Tests\Functional\DbWebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ArchivedTest extends DbWebTestCase
{
    private const BASE_URL = '/teachers/show/all';

    private const BASE_METHOD = Request::METHOD_GET;

    /**
     * Получение списка архивных преподавателей, отсортированных по возрастанию идентификаторов
     */
    public function testArchived(): void
    {
        $this->client->request(self::BASE_METHOD, self::BASE_URL . '/archived');

        $data = $this->getJsonData(Response::HTTP_OK);

        self::assertEquals([
            [
                'id'          => TeacherFixture::TEACHER_2_ID,
                'user_id'     => UserFixture::ALREADY_REQUESTED_UUID,
                'alias'       => 'already-request-user',
                'name'        => [
                    'first' => 'First',
                    'last'  => 'Last',
                ],
                'description' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient.',
                'price'       => 100,
                'rating'      => 0.0,
                'status'      => 'archived',
                'created_at'  => '2020-01-01 16:00:00',
            ],
        ], $data);
    }
}

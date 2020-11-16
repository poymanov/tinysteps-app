<?php

declare(strict_types=1);

namespace App\Tests\Functional\Lesson\Teacher\Show\All;

use App\Tests\Fixtures\GoalFixture;
use App\Tests\Fixtures\TeacherFixture;
use App\Tests\Fixtures\UserFixture;
use App\Tests\Functional\DbWebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ActiveTest extends DbWebTestCase
{
    private const BASE_URL = '/teachers/show/all/active';

    private const BASE_METHOD = Request::METHOD_GET;

    /**
     * Получение списка активных преподавателей, отсортированных по возрастанию идентификаторов
     */
    public function testActive(): void
    {
        $this->client->request(self::BASE_METHOD, self::BASE_URL);

        $data = $this->getJsonData(Response::HTTP_OK);

        self::assertEquals([
            [
                'id'          => TeacherFixture::TEACHER_1_ID,
                'user_id'     => UserFixture::EXISTING_UUID,
                'alias'       => 'existing-user',
                'name'        => [
                    'first' => 'existing',
                    'last'  => 'user',
                ],
                'description' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient.',
                'price'       => 100,
                'rating'      => 0.0,
                'status'      => 'active',
                'created_at'  => '2020-01-01 15:00:00',
            ],
            [
                'id'          => TeacherFixture::TEACHER_3_ID,
                'user_id'     => UserFixture::REQUEST_RESET_TOKEN_UUID,
                'alias'       => 'request-reset-token',
                'name'        => [
                    'first' => 'First',
                    'last'  => 'Last',
                ],
                'description' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient.',
                'price'       => 100,
                'rating'      => 0.0,
                'status'      => 'active',
                'created_at'  => '2020-01-01 17:00:00',
            ],
        ], $data);
    }

    /**
     * Запрос цели c uuid в неправильном формате для  списка активных преподавателей по цели обучения
     */
    public function testActiveByGoalNotValidUuid(): void
    {
        $this->assertNotValidUuid(self::BASE_METHOD, self::BASE_URL . '/goal/' . '123');
    }

    /**
     * Запрос несуществующей цели для  списка активных преподавателей по цели обучения
     */
    public function testActiveByGoalNotFound(): void
    {
        $this->assertNotFound(self::BASE_METHOD, self::BASE_URL . '/goal/' . '00000000-0000-0000-0000-000000000099');
    }

    /**
     * Получение списка активных преподавателей по цели обучения, отсортированных по возрастанию идентификаторов
     */
    public function testActiveByGoal(): void
    {
        $this->client->request(self::BASE_METHOD, self::BASE_URL . '/goal/' . GoalFixture::GOAL_2_ID);

        $data = $this->getJsonData(Response::HTTP_OK);

        self::assertEquals([
            [
                'id'          => TeacherFixture::TEACHER_1_ID,
                'user_id'     => UserFixture::EXISTING_UUID,
                'alias'       => 'existing-user',
                'name'        => [
                    'first' => 'existing',
                    'last'  => 'user',
                ],
                'description' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient.',
                'price'       => 100,
                'rating'      => 0.0,
                'status'      => 'active',
                'created_at'  => '2020-01-01 15:00:00',
            ],
        ], $data);
    }
}

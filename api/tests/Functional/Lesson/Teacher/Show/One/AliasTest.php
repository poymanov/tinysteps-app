<?php

declare(strict_types=1);

namespace App\Tests\Functional\Lesson\Teacher\Show\One;

use App\Tests\Fixtures\TeacherFixture;
use App\Tests\Fixtures\UserFixture;
use App\Tests\Functional\DbWebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AliasTest extends DbWebTestCase
{
    private const BASE_URL = '/teachers/show/one/alias/';

    private const BASE_URL_TEACHER_1 = self::BASE_URL . 'existing-user';

    private const BASE_METHOD = Request::METHOD_GET;

    /**
     * Запрос несуществующего преподавателя
     */
    public function testNotFound(): void
    {
        $this->assertNotFound(self::BASE_METHOD, self::BASE_URL . 'test');
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
            'name'        => [
                'first' => 'existing',
                'last'  => 'user',
            ],
            'description' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient.',
            'price'       => 100,
            'rating'      => 0.0,
            'status'      => 'active',
            'created_at'  => '2020-01-01 15:00:00',
        ], $data);
    }
}

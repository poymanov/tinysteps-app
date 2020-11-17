<?php

declare(strict_types=1);

namespace App\Tests\Functional\Lesson\Goal\Show\One;

use App\Tests\Fixtures\GoalFixture;
use App\Tests\Functional\DbWebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AliasTest extends DbWebTestCase
{
    private const BASE_URL = '/goals/show/one/alias/';

    private const BASE_URL_GOAL_1 = self::BASE_URL . 'dla-pereezda';

    private const BASE_METHOD = Request::METHOD_GET;

    /**
     * Запрос несуществующей цели обучения
     */
    public function testNotFound(): void
    {
        $this->assertNotFound(self::BASE_METHOD, self::BASE_URL . 'test');
    }

    /**
     * Успешный запрос цели обучения
     */
    public function testSuccess(): void
    {
        $this->authAsAdmin();

        $this->client->request(self::BASE_METHOD, self::BASE_URL_GOAL_1);

        $data = $this->getJsonData(Response::HTTP_OK);

        self::assertEquals([
            'id'         => GoalFixture::GOAL_1_ID,
            'alias'      => 'dla-pereezda',
            'name'       => 'Для переезда',
            'status'     => 'active',
            'sort'       => 1,
            'icon'       => '🚜',
            'created_at' => '2020-01-01 10:00:00',
        ], $data);
    }
}

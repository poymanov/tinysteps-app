<?php

declare(strict_types=1);

namespace App\Tests\Functional\Lesson\Goal\Show;

use App\Tests\Fixtures\GoalFixture;
use App\Tests\Functional\DbWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ListTest extends DbWebTestCase
{
    /**
     * Получение списка всех целей обучения согласно порядку сортировки
     */
    public function testAll(): void
    {
        $this->client->request('GET', '/goals/show/all');

        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        $data = $this->getJsonData();

        self::assertEquals([
            [
                'id'         => GoalFixture::GOAL_1_ID,
                'alias'      => 'dla-pereezda',
                'name'       => 'Для переезда',
                'status'     => 'active',
                'sort'       => 1,
                'created_at' => '2020-01-01 10:00:00',
            ],
            [
                'id'         => GoalFixture::GOAL_2_ID,
                'alias'      => 'dla-uceby',
                'name'       => 'Для учебы',
                'status'     => 'active',
                'sort'       => 2,
                'created_at' => '2020-01-02 10:00:00',
            ],
            [
                'id'         => GoalFixture::GOAL_3_ID,
                'alias'      => 'dla-putesestvij',
                'name'       => 'Для путешествий',
                'status'     => 'active',
                'sort'       => 3,
                'created_at' => '2020-01-03 10:00:00',
            ],
            [
                'id'         => GoalFixture::GOAL_4_ID,
                'alias'      => 'dla-raboty',
                'name'       => 'Для работы',
                'status'     => 'active',
                'sort'       => 4,
                'created_at' => '2020-01-04 10:00:00',
            ],
            [
                'id'         => GoalFixture::GOAL_5_ID,
                'alias'      => 'procee',
                'name'       => 'Прочее',
                'status'     => 'archived',
                'sort'       => 5,
                'created_at' => '2020-01-05 10:00:00',
            ],
        ], $data);
    }

    /**
     * Получение списка активных целей обучения согласно порядку сортировки
     */
    public function testActive(): void
    {
        $this->client->request('GET', '/goals/show/active');

        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        $data = $this->getJsonData();

        self::assertEquals([
            [
                'id'         => GoalFixture::GOAL_1_ID,
                'alias'      => 'dla-pereezda',
                'name'       => 'Для переезда',
                'status'     => 'active',
                'sort'       => 1,
                'created_at' => '2020-01-01 10:00:00',
            ],
            [
                'id'         => GoalFixture::GOAL_2_ID,
                'alias'      => 'dla-uceby',
                'name'       => 'Для учебы',
                'status'     => 'active',
                'sort'       => 2,
                'created_at' => '2020-01-02 10:00:00',
            ],
            [
                'id'         => GoalFixture::GOAL_3_ID,
                'alias'      => 'dla-putesestvij',
                'name'       => 'Для путешествий',
                'status'     => 'active',
                'sort'       => 3,
                'created_at' => '2020-01-03 10:00:00',
            ],
            [
                'id'         => GoalFixture::GOAL_4_ID,
                'alias'      => 'dla-raboty',
                'name'       => 'Для работы',
                'status'     => 'active',
                'sort'       => 4,
                'created_at' => '2020-01-04 10:00:00',
            ],
        ], $data);
    }

    /**
     * Получение списка архивных целей обучения согласно порядку сортировки
     */
    public function testArchived(): void
    {
        $this->client->request('GET', '/goals/show/archived');

        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        $data = $this->getJsonData();

        self::assertEquals([
            [
                'id'         => GoalFixture::GOAL_5_ID,
                'alias'      => 'procee',
                'name'       => 'Прочее',
                'status'     => 'archived',
                'sort'       => 5,
                'created_at' => '2020-01-05 10:00:00',
            ],
        ], $data);
    }
}

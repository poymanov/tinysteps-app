<?php

declare(strict_types=1);

namespace App\Tests\Functional\Profile;

use App\DataFixtures\UserFixture;
use App\Tests\Functional\DbWebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ShowTest extends DbWebTestCase
{
    private const BASE_URL = '/profile/show';

    /**
     * Попытка получение профиля неавторизованным пользователем
     */
    public function testNotAuth(): void
    {
        $this->assertNotAuth(Request::METHOD_GET, self::BASE_URL);
    }

    /**
     * Успешное получение профиля пользователя
     */
    public function testSuccess(): void
    {
        $this->authAsUser();

        $this->client->request(Request::METHOD_GET, self::BASE_URL);

        self::assertResponseIsSuccessful();

        $data = $this->getJsonData();

        self::assertEquals([
            'id'     => UserFixture::USER_1_ID,
            'email'  => 'user@app.test',
            'name'   => [
                'first' => 'First',
                'last'  => 'Last',
                'full'  => 'First Last',
            ],
            'status' => 'active',
            'role'   => 'ROLE_USER',
        ], $data);
    }
}

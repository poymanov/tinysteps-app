<?php

declare(strict_types=1);

namespace App\Tests\Functional\Auth\SignUp;

use App\Tests\Functional\DbWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ConfirmTest extends DbWebTestCase
{
    private const BASE_URL = '/auth/signup';

    /**
     * Подтверждение регистрации по несуществующему токену
     */
    public function testNotExistedToken(): void
    {
        $this->client->request('GET',self::BASE_URL . '/123');

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);

        $data = $this->getJsonData();

        self::assertEquals([
            'error' => [
                'message' => 'Неизвестный токен.',
            ],
        ], $data);
    }

    /**
     * Успешное подтверждение регистрации
     */
    public function testSuccess(): void
    {
        $this->client->request('GET',self::BASE_URL . '/not-confirmed-token');

        self::assertResponseIsSuccessful();

        $data = $this->getJsonData();

        self::assertEquals(['message' => 'Ваш email успешно подтвержден.'], $data);
    }
}

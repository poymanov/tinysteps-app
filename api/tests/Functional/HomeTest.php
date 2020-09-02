<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Symfony\Component\HttpFoundation\Response;

class HomeTest extends DbWebTestCase
{
    /**
     * Отображение корневого адреса
     */
    public function testIndex()
    {
        $this->client->request('GET', '/');
        self::assertResponseIsSuccessful();

        $data = $this->getJsonData();

        self::assertEquals(['name' => 'JSON API'], $data);
    }

    /**
     * Отображение 404 ошибки
     */
    public function testNotFound()
    {
        $this->client->request('GET', '/123');

        $data = $this->getJsonData(Response::HTTP_NOT_FOUND);

        self::assertEquals([
            'error' => [
                'message' => 'Неизвестный запрос',
            ]
        ], $data);
    }

    /**
     * Отображение 405 ошибки
     */
    public function testMethodNotAllowed()
    {
        $this->client->request('POST', '/');

        $data = $this->getJsonData(Response::HTTP_METHOD_NOT_ALLOWED);

        self::assertEquals([
            'error' => [
                'message' => 'Неподдерживаемый тип запроса',
            ]
        ], $data);
    }
}

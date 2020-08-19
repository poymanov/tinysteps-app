<?php

declare(strict_types=1);

namespace App\Tests\Functional;

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
}

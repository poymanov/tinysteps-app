<?php

namespace App\Tests\Functional\Helpers;

use App\DataFixtures\UserFixture;
use App\Tests\Functional\DbWebTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

trait AuthTrait
{
    /**
     * Аутентификация с ролью ROLE_USER
     */
    public function authAsUser(): void
    {
        $this->auth(UserFixture::userCredentials());
    }

    /**
     * Аутентификация с ролью ROLE_ADMIN
     */
    public function authAsAdmin(): void
    {
        $this->auth(UserFixture::adminCredentials());
    }

    /**
     * Аутентификация пользовательского соединения
     *
     * @param array|null $credentials
     */
    public function auth(array $credentials): void
    {
        /** @var $testCase DbWebTestCase */
        $testCase = $this;

        $testCase->client->setServerParameters([
            'PHP_AUTH_USER' => $credentials['email'],
            'PHP_AUTH_PW'   => $credentials['password'],
        ]);
    }
}

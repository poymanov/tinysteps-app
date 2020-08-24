<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model\User\Entity\User\Reset;

use App\Model\User\Entity\User\ResetToken;
use App\Tests\Builder\User\UserBuilder;
use DateTimeImmutable;
use Exception;
use PHPUnit\Framework\TestCase;

class ResetTest extends TestCase
{
    /**
     * Сброс пароля не был запрошен
     *
     * @throws Exception
     */
    public function testNotRequested(): void
    {
        $user = (new UserBuilder())->viaEmail()->confirmed()->build();

        $now = new DateTimeImmutable();

        $this->expectExceptionMessage('Сброс пароля не был запрошен.');
        $user->passwordReset($now, 'hash');
    }

    /**
     * Токен сброса пароля уже истек
     *
     * @throws Exception
     */
    public function testExpiredToken(): void
    {
        $user = (new UserBuilder())->viaEmail()->confirmed()->build();

        $now   = new DateTimeImmutable();
        $token = new ResetToken('token', $now);

        $user->requestPasswordReset($token, $now);

        $this->expectExceptionMessage('Токен сброса пароля уже истек.');
        $user->passwordReset($now->modify('+1 day'), 'hash');
    }

    /**
     * Пароль успешно сброшен
     *
     * @throws Exception
     */
    public function testSuccess(): void
    {
        $user = (new UserBuilder())->viaEmail()->confirmed()->build();

        $now   = new DateTimeImmutable();
        $token = new ResetToken('token', $now->modify('+1 day'));

        $user->requestPasswordReset($token, $now);

        self::assertNotNull($user->getResetToken());

        $user->passwordReset($now, $hash = 'hash');

        self::assertNull($user->getResetToken());
        self::assertEquals($hash, $user->getPasswordHash());
    }
}

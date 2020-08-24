<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model\User\Entity\User\SignUp;

use App\Tests\Builder\User\UserBuilder;
use Exception;
use PHPUnit\Framework\TestCase;

class ConfirmTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testSuccess(): void
    {
        $user = (new UserBuilder())->viaEmail()->build();

        $user->confirmSignUp();

        self::assertFalse($user->getStatus()->isWait());
        self::assertTrue($user->getStatus()->isActive());
        self::assertNull($user->getConfirmToken());
    }

    /**
     * @throws Exception
     */
    public function testAlready(): void
    {
        $user = (new UserBuilder())->viaEmail()->build();

        $user->confirmSignUp();
        $this->expectExceptionMessage('Токен уже подтвержден.');
        $user->confirmSignUp();
    }
}

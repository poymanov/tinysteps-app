<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model\User\Entity\User\SignUp;

use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\Name;
use App\Model\User\Entity\User\User;
use DateTimeImmutable;
use Exception;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testSuccess(): void
    {
        $user = User::signUpByEmail(
            $id = Id::next(),
            $createdAt = new DateTimeImmutable(),
            $name = new Name('First', 'Last'),
            $email = new Email('test@test.ru'),
            $hash = 'hash',
            $token = 'token'
        );

        self::assertTrue($user->getStatus()->isWait());
        self::assertFalse($user->getStatus()->isActive());
        self::assertEquals($id, $user->getId());
        self::assertEquals($createdAt, $user->getCreatedAt());
        self::assertEquals($name, $user->getName());
        self::assertEquals($email, $user->getEmail());
        self::assertEquals($hash, $user->getPasswordHash());
        self::assertEquals($token, $user->getConfirmToken());
        self::assertTrue($user->getRole()->isUser());
    }
}

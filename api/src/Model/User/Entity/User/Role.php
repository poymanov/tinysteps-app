<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User;

use Webmozart\Assert\Assert;

class Role
{
    private const USER = 'ROLE_USER';
    private const ADMIN = 'ROLE_ADMIN';

    /**
     * @var string
     */
    private $name;

    /**
     * @param $name
     */
    public function __construct($name)
    {
        Assert::oneOf($name, [self::USER, self::ADMIN]);
        $this->name = $name;
    }

    /**
     * @return static
     */
    public static function user(): self
    {
        return new self(self::USER);
    }

    /**
     * @return static
     */
    public static function admin(): self
    {
        return new self(self::ADMIN);
    }

    /**
     * @return bool
     */
    public function isUser(): bool
    {
        return $this->name === self::USER;
    }

    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->name === self::ADMIN;
    }

    public function isEqual(self $role): bool
    {
        return $this->getName() === $role->getName();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}

<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User;

use Webmozart\Assert\Assert;

class Status
{
    private const STATUS_WAIT = 'wait';

    private const STATUS_ACTIVE = 'active';

    private const STATUS_BLOCKED = 'blocked';

    private const STATUSES = [
        self::STATUS_WAIT,
        self::STATUS_ACTIVE,
        self::STATUS_BLOCKED,
    ];

    /**
     * @var string
     */
    private $value;

    /**
     * Email constructor.
     * @param string $value
     */
    public function __construct(string $value)
    {
        Assert::notEmpty($value);
        Assert::oneOf($value, self::STATUSES);

        $this->value = $value;
    }

    /**
     * @return static
     */
    public static function wait(): self
    {
        return new self(self::STATUS_WAIT);
    }

    /**
     * @return static
     */
    public static function active(): self
    {
        return new self(self::STATUS_ACTIVE);
    }

    /**
     * @return static
     */
    public static function blocked(): self
    {
        return new self(self::STATUS_BLOCKED);
    }

    /**
     * @return bool
     */
    public function isWait(): bool
    {
        return $this->value === self::STATUS_WAIT;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->value === self::STATUS_ACTIVE;
    }

    /**
     * @return bool
     */
    public function isBlocked(): bool
    {
        return $this->value === self::STATUS_BLOCKED;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param Status $other
     * @return bool
     */
    public function isEqual(self $other): bool
    {
        return $this->getValue() === $other->getValue();
    }
}

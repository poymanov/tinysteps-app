<?php

declare(strict_types=1);

namespace App\Model\Lesson\Entity\Teacher;

use Webmozart\Assert\Assert;

class Status
{
    private const STATUS_ACTIVE = 'active';

    private const STATUS_ARCHIVED = 'archived';

    private const STATUSES = [
        self::STATUS_ACTIVE,
        self::STATUS_ARCHIVED,
    ];

    private const LABELS = [
        self::STATUS_ACTIVE => 'Активен',
        self::STATUS_ARCHIVED => 'В архиве',
    ];

    /**
     * @var string
     */
    private $value;

    /**
     * @param string $value
     */
    public function __construct(string $value)
    {
        $value = mb_strtolower($value);

        Assert::notEmpty($value);
        Assert::oneOf($value, self::STATUSES, 'Неизвестный статус.');

        $this->value = $value;
    }

    /**
     * @return static
     */
    public static function archived(): self
    {
        return new self(self::STATUS_ARCHIVED);
    }

    /**
     * @return static
     */
    public static function active(): self
    {
        return new self(self::STATUS_ACTIVE);
    }

    /**
     * @param string $value
     *
     * @return bool
     */
    public static function isEqualActive(string $value): bool
    {
        return $value === self::STATUS_ACTIVE;
    }

    /**
     * @return bool
     */
    public function isArchived(): bool
    {
        return $this->value === self::STATUS_ARCHIVED;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->value === self::STATUS_ACTIVE;
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
     *
     * @return bool
     */
    public function isEqual(self $other): bool
    {
        return $this->getValue() === $other->getValue();
    }

    /**
     * Получение заголовка статуса
     *
     * @return string
     */
    public function getLabel(): string
    {
        return self::LABELS[$this->value] ?? $this->value;
    }
}

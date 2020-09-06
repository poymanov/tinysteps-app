<?php

declare(strict_types=1);

namespace App\Model\Lesson\Entity\Teacher;

use Webmozart\Assert\Assert;

class Alias
{
    private const SLUG_PATTERN = '/^[a-z0-9-]+$/im';

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
        Assert::regex($value, self::SLUG_PATTERN, 'Неправильный формат alias.');

        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param Alias $other
     * @return bool
     */
    public function isEqual(self $other): bool
    {
        return $this->getValue() === $other->getValue();
    }
}

<?php

declare(strict_types=1);

namespace App\Model\Lesson\Entity\Teacher;

use Webmozart\Assert\Assert;

class Description
{
    public const MIN_LENGTH = 150;

    /**
     * @var string
     */
    private $value;

    /**
     * @param string $value
     */
    public function __construct(string $value)
    {
        Assert::notEmpty($value);
        Assert::minLength($value, self::MIN_LENGTH, 'Минимальная длина описания (символов) - ' . self::MIN_LENGTH);

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
     * @param Description $other
     *
     * @return bool
     */
    public function isEqual(self $other): bool
    {
        return $this->getValue() === $other->getValue();
    }
}

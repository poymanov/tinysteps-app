<?php

declare(strict_types=1);


namespace App\Model\Lesson\Entity\Teacher;


use Webmozart\Assert\Assert;

class Rating
{
    /**
     * Максимальное значение рейтинга
     */
    private const MAX = 5;

    /**
     * @var float
     */
    private $value;

    /**
     * @param float $value
     */
    public function __construct(float $value)
    {
        Assert::greaterThanEq($value, 0, 'Значение рейтинга не может быть отрицательным.');
        Assert::lessThanEq($value, 5, 'Значение рейтинга не может быть больше ' . self::MAX . '.');

        $this->value = $value;
    }

    public static function default(): self
    {
        return new self(0);
    }

    /**
     * @return float
     */
    public function getValue(): float
    {
        return $this->value;
    }

    /**
     * @param Price $other
     *
     * @return bool
     */
    public function isEqual(Price $other): bool
    {
        return $this->getValue() === $other->getValue();
    }

    /**
     * @internal for postLoad callback
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->value);
    }
}

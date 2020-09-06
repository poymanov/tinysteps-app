<?php

declare(strict_types=1);


namespace App\Model\Lesson\Entity\Teacher;


use Webmozart\Assert\Assert;

class Rating
{
    /**
     * @var float
     */
    private $value;

    /**
     * @param float $value
     */
    public function __construct(float $value)
    {
        Assert::greaterThanEq($value, 0);

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
}

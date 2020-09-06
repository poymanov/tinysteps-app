<?php

declare(strict_types=1);


namespace App\Model\Lesson\Entity\Teacher;


use Webmozart\Assert\Assert;

class Price
{
    /**
     * @var int
     */
    private $value;

    /**
     * @param int $value
     */
    public function __construct(int $value)
    {
        Assert::greaterThan($value, 0, 'Значение цены должно быть больше 0.');

        $this->value = $value;
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * @param Price $other
     *
     * @return bool
     */
    public function isEqual(self $other): bool
    {
        return $this->getValue() === $other->getValue();
    }
}

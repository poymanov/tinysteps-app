<?php

declare(strict_types=1);

namespace App\Tests\Builder\Lesson;

use App\Model\Lesson\Entity\Teacher\Alias;
use App\Model\Lesson\Entity\Teacher\Description;
use App\Model\Lesson\Entity\Teacher\Id;
use App\Model\Lesson\Entity\Teacher\Price;
use App\Model\Lesson\Entity\Teacher\Teacher;
use DateTimeImmutable;
use Exception;

class TeacherBuilder
{
    /**
     * @var Id
     */
    private Id $id;

    /**
     * @var string
     */
    private string $userId = '';

    /**
     * @var Alias
     */
    private Alias $alias;

    /**
     * @var Description
     */
    private Description $description;

    /**
     * @var Price
     */
    private Price $price;

    /**
     * @var DateTimeImmutable
     */
    private DateTimeImmutable $createdAt;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->id          = Id::next();
        $this->createdAt   = new DateTimeImmutable();
        $this->price       = new Price(100);
        $this->description = new Description('Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient.');
    }

    /**
     * @param Id $id
     *
     * @return $this
     */
    public function withId(Id $id): self
    {
        $clone     = clone $this;
        $clone->id = $id;

        return $clone;
    }

    /**
     * @param string $userId
     *
     * @return $this
     */
    public function withUserId(string $userId): self
    {
        $clone         = clone $this;
        $clone->userId = $userId;

        return $clone;
    }

    /**
     * @param Alias $alias
     *
     * @return $this
     */
    public function withAlias(Alias $alias): self
    {
        $clone        = clone $this;
        $clone->alias = $alias;

        return $clone;
    }

    /**
     * @param Description $description
     *
     * @return $this
     */
    public function withDescription(Description $description): self
    {
        $clone              = clone $this;
        $clone->description = $description;

        return $clone;
    }

    /**
     * @param Price $price
     *
     * @return $this
     */
    public function withPrice(Price $price): self
    {
        $clone        = clone $this;
        $clone->price = $price;

        return $clone;
    }

    /**
     * @param DateTimeImmutable $date
     *
     * @return $this
     */
    public function withCreatedAt(DateTimeImmutable $date): self
    {
        $clone            = clone $this;
        $clone->createdAt = $date;

        return $clone;
    }

    /**
     * @return Teacher
     * @throws Exception
     */
    public function build(): Teacher
    {
        return new Teacher($this->id, $this->userId, $this->alias, $this->description, $this->price, $this->createdAt);
    }
}

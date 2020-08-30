<?php

declare(strict_types=1);

namespace App\Tests\Builder\Lesson;

use App\Model\Lesson\Entity\Goal\Goal;
use App\Model\Lesson\Entity\Goal\Id;
use DateTimeImmutable;
use Exception;

class GoalBuilder
{
    /**
     * @var Id
     */
    private Id $id;

    /**
     * @var string
     */
    private string $alias;

    /**
     * @var string
     */
    private string $name;

    /**
     * @var int
     */
    private int $sort;

    /**
     * @var DateTimeImmutable
     */
    private $createdAt;

    /**
     * UserBuilder constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->id        = Id::next();
        $this->alias     = 'cel-zanjatija';
        $this->name      = 'Цель занятия';
        $this->sort      = 1;
        $this->createdAt = new DateTimeImmutable();
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
     * @param string $name
     *
     * @return $this
     */
    public function withName(string $name): self
    {
        $clone       = clone $this;
        $clone->name = $name;

        return $clone;
    }

    /**
     * @param string $alias
     *
     * @return $this
     */
    public function withAlias(string $alias): self
    {
        $clone        = clone $this;
        $clone->alias = $alias;

        return $clone;
    }

    /**
     * @param int $sort
     *
     * @return $this
     */
    public function withSort(int $sort): self
    {
        $clone       = clone $this;
        $clone->sort = $sort;

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
     * @return Goal
     * @throws Exception
     */
    public function build(): Goal
    {
        return new Goal($this->id, $this->alias, $this->name, $this->sort, $this->createdAt);
    }

}

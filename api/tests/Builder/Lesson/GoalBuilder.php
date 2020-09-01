<?php

declare(strict_types=1);

namespace App\Tests\Builder\Lesson;

use App\Model\Lesson\Entity\Goal\Alias;
use App\Model\Lesson\Entity\Goal\Goal;
use App\Model\Lesson\Entity\Goal\Id;
use App\Model\Lesson\Entity\Goal\Status;
use DateTimeImmutable;
use Exception;

class GoalBuilder
{
    /**
     * @var Id
     */
    private Id $id;

    /**
     * @var Alias
     */
    private Alias $alias;

    /**
     * @var string
     */
    private string $name;

    /**
     * @var Status|null
     */
    private ?Status $status = null;

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
        $this->alias     = new Alias('cel-zanjatija');
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
     * @param Status $status
     *
     * @return $this
     */
    public function withStatus(Status $status): self
    {
        $clone         = clone $this;
        $clone->status = $status;

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
        $goal = new Goal($this->id, $this->alias, $this->name, $this->sort, $this->createdAt);

        if ($this->status) {
            $goal->changeStatus($this->status);
        }

        return $goal;
    }

}

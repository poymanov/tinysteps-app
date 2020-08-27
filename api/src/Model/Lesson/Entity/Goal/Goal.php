<?php

declare(strict_types=1);

namespace App\Model\Lesson\Entity\Goal;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="lesson_goals", uniqueConstraints={
 *  @ORM\UniqueConstraint(columns={"alias"}),
 *  @ORM\UniqueConstraint(columns={"name"}),
 * })
 */
class Goal
{
    /**
     * @var Id
     * @ORM\Column(type="lesson_goal_id")
     * @ORM\Id
     */
    private Id $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private string $alias;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private string $name;

    /**
     * @var Status
     * @ORM\Column(type="lesson_goal_status")
     */
    private Status $status;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private int $sort;

    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     */
    private DateTimeImmutable $createdAt;

    /**
     * @param Id                $id
     * @param string            $alias
     * @param string            $name
     * @param int               $sort
     * @param DateTimeImmutable $createdAt
     */
    public function __construct(Id $id, string $alias, string $name, int $sort, DateTimeImmutable $createdAt)
    {
        $this->id        = $id;
        $this->alias     = $alias;
        $this->name      = $name;
        $this->sort      = $sort;
        $this->createdAt = $createdAt;
        $this->status    = Status::active();
    }

    /**
     * @return Id
     */
    public function getId(): Id
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getAlias(): string
    {
        return $this->alias;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Status
     */
    public function getStatus(): Status
    {
        return $this->status;
    }

    /**
     * @return int
     */
    public function getSort(): int
    {
        return $this->sort;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}

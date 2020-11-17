<?php

declare(strict_types=1);

namespace App\Model\Lesson\Entity\Goal;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use DomainException;

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
     * @var Alias
     * @ORM\Column(type="lesson_goal_alias")
     */
    private Alias $alias;

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
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private string $icon;

    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     */
    private DateTimeImmutable $createdAt;

    /**
     * @param Id                $id
     * @param Alias             $alias
     * @param string            $name
     * @param int               $sort
     * @param DateTimeImmutable $createdAt
     */
    public function __construct(Id $id, Alias $alias, string $name, int $sort, DateTimeImmutable $createdAt)
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
     * @return Alias
     */
    public function getAlias(): Alias
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
     * @return string
     */
    public function getIcon(): string
    {
        return $this->icon;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * Изменение имени
     *
     * @param string $name
     */
    public function changeName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Изменение alias
     *
     * @param Alias $alias
     */
    public function changeAlias(Alias $alias): void
    {
        $this->alias = $alias;
    }

    /**
     * Изменение статуса
     *
     * @param Status $status
     */
    public function changeStatus(Status $status): void
    {
        $this->status = $status;
    }

    /**
     * Изменение порядка сортировки
     *
     * @param int $sort
     */
    public function changeSort(int $sort): void
    {
        if ($sort <= 0) {
            throw new DomainException('Значение порядка должно быть больше нуля.');
        }

        $this->sort = $sort;
    }

    /**
     * Изменение иконки
     *
     * @param string $icon
     */
    public function changeIcon(string $icon): void
    {
        $this->icon = $icon;
    }
}

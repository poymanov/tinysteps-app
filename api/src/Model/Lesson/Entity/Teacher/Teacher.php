<?php

declare(strict_types=1);

namespace App\Model\Lesson\Entity\Teacher;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use DomainException;

/**
 * @ORM\Entity
 * @ORM\Table(name="lesson_teachers", uniqueConstraints={
 *  @ORM\UniqueConstraint(columns={"alias"}),
 *  @ORM\UniqueConstraint(columns={"user_id"}),
 * })
 */
class Teacher
{
    /**
     * @var Id
     * @ORM\Column(type="lesson_teacher_id")
     * @ORM\Id
     */
    private Id $id;

    /**
     * @var string
     * @ORM\Column(type="guid")
     */
    private string $userId;

    /**
     * @var Alias
     * @ORM\Column(type="lesson_teacher_alias")
     */
    private Alias $alias;

    /**
     * @var Description
     * @ORM\Column(type="lesson_teacher_description")
     */
    private Description $description;

    /**
     * @var Price
     * @ORM\Column(type="lesson_teacher_price")
     */
    private Price $price;

    /**
     * @var Rating
     * @ORM\Column(type="lesson_teacher_rating")
     */
    private Rating $rating;

    /**
     * @var Status
     * @ORM\Column(type="lesson_teacher_status")
     */
    private Status $status;

    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     */
    private DateTimeImmutable $createdAt;

    /**
     * @param Id                $id
     * @param string            $userId
     * @param Alias             $alias
     * @param Description       $description
     * @param Price             $price
     * @param DateTimeImmutable $createdAt
     */
    public function __construct(Id $id, string $userId, Alias $alias, Description $description, Price $price, DateTimeImmutable $createdAt)
    {
        $this->id          = $id;
        $this->userId      = $userId;
        $this->alias       = $alias;
        $this->description = $description;
        $this->price       = $price;
        $this->createdAt   = $createdAt;
        $this->status      = Status::active();
        $this->rating      = Rating::default();
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
    public function getUserId(): string
    {
        return $this->userId;
    }

    /**
     * @return Alias
     */
    public function getAlias(): Alias
    {
        return $this->alias;
    }

    /**
     * @return Description
     */
    public function getDescription(): Description
    {
        return $this->description;
    }

    /**
     * @return Price
     */
    public function getPrice(): Price
    {
        return $this->price;
    }

    /**
     * @return Rating
     */
    public function getRating(): Rating
    {
        return $this->rating;
    }

    /**
     * @return Status
     */
    public function getStatus(): Status
    {
        return $this->status;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * Изменение статуса
     *
     * @param Status $status
     */
    public function changeStatus(Status $status): void
    {
        if ($this->getStatus()->isEqual($status)) {
            throw new DomainException('Преподаватель уже находится в данном статусе.');
        }

        $this->status = $status;
    }

    /**
     * Изменение alias
     *
     * @param Alias $alias
     */
    public function changeAlias(Alias $alias): void
    {
        if ($this->getStatus()->isArchived()) {
            throw new DomainException('Преподаватель находится в архиве и недоступен для изменений.');
        }

        $this->alias = $alias;
    }

    /**
     * Изменение описания
     *
     * @param Description $description
     */
    public function changeDescription(Description $description): void
    {
        if ($this->getStatus()->isArchived()) {
            throw new DomainException('Преподаватель находится в архиве и недоступен для изменений.');
        }

        $this->description = $description;
    }
}

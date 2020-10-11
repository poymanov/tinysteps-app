<?php

declare(strict_types=1);

namespace App\Model\Lesson\Entity\Schedule;

use App\Model\Lesson\Entity\Teacher\Teacher;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * @ORM\Entity
 * @ORM\Table(name="lesson_schedules", uniqueConstraints={
 *  @ORM\UniqueConstraint(columns={"teacher_id", "date"}),
 * })
 */
class Schedule
{
    /**
     * @var Id
     * @ORM\Column(type="lesson_schedule_id")
     * @ORM\Id
     */
    private Id $id;

    /**
     * @ManyToOne(targetEntity="App\Model\Lesson\Entity\Teacher\Teacher")
     * @JoinColumn(name="teacher_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private Teacher $teacher;

    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     */
    private DateTimeImmutable $date;

    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     */
    private DateTimeImmutable $createdAt;

    /**
     * @param Id                $id
     * @param Teacher           $teacher
     * @param DateTimeImmutable $date
     * @param DateTimeImmutable $createdAt
     */
    public function __construct(Id $id, Teacher $teacher, DateTimeImmutable $date, DateTimeImmutable $createdAt)
    {
        $this->id        = $id;
        $this->teacher   = $teacher;
        $this->date      = $date;
        $this->createdAt = $createdAt;
    }

    /**
     * @return Id
     */
    public function getId(): Id
    {
        return $this->id;
    }

    /**
     * @return Teacher
     */
    public function getTeacher(): Teacher
    {
        return $this->teacher;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}

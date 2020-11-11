<?php

declare(strict_types=1);

namespace App\Model\Lesson\Entity\Lesson;

use App\Model\Lesson\Entity\Schedule\Schedule;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * @ORM\Entity
 * @ORM\Table(name="lesson_lessons", uniqueConstraints={
 *  @ORM\UniqueConstraint(columns={"user_id", "schedule_id"}),
 * })
 */
class Lesson
{
    /**
     * @var Id
     * @ORM\Column(type="lesson_lesson_id")
     * @ORM\Id
     */
    private Id $id;

    /**
     * @var string
     * @ORM\Column(type="guid")
     */
    private string $userId;

    /**
     * @ManyToOne(targetEntity="App\Model\Lesson\Entity\Schedule\Schedule")
     * @JoinColumn(name="schedule_id", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     */
    private Schedule $schedule;

    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     */
    private DateTimeImmutable $createdAt;

    /**
     * @param Id                $id
     * @param string            $userId
     * @param Schedule          $schedule
     * @param DateTimeImmutable $createdAt
     */
    public function __construct(Id $id, string $userId, Schedule $schedule, DateTimeImmutable $createdAt)
    {
        $this->id        = $id;
        $this->userId    = $userId;
        $this->schedule  = $schedule;
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
     * @return string
     */
    public function getUserId(): string
    {
        return $this->userId;
    }

    /**
     * @return Schedule
     */
    public function getSchedule(): Schedule
    {
        return $this->schedule;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}

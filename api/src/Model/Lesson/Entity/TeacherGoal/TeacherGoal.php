<?php

declare(strict_types=1);

namespace App\Model\Lesson\Entity\TeacherGoal;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="lesson_teachers_goals", uniqueConstraints={
 *  @ORM\UniqueConstraint(columns={"teacher_id", "goal_id"})
 * })
 */
class TeacherGoal
{
    /**
     * @var Id
     * @ORM\Column(type="lesson_teacher_goal_id")
     * @ORM\Id
     */
    private Id $id;

    /**
     * @var string
     * @ORM\Column(type="guid")
     */
    private string $teacherId;

    /**
     * @var string
     * @ORM\Column(type="guid")
     */
    private string $goalId;

    /**
     * @param Id     $id
     * @param string $teacherId
     * @param string $goalId
     */
    public function __construct(Id $id, string $teacherId, string $goalId)
    {
        $this->id        = $id;
        $this->teacherId = $teacherId;
        $this->goalId    = $goalId;
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
    public function getTeacherId(): string
    {
        return $this->teacherId;
    }

    /**
     * @return string
     */
    public function getGoalId(): string
    {
        return $this->goalId;
    }
}

<?php

declare(strict_types=1);

namespace App\Model\Lesson\UseCase\TeacherGoal\Remove;

use App\Model\Flusher;
use App\Model\Lesson\Entity\Goal\GoalRepository;
use App\Model\Lesson\Entity\Goal\Id as GoalId;
use App\Model\Lesson\Entity\Teacher\Id as TeacherId;
use App\Model\Lesson\Entity\Teacher\TeacherRepository;
use App\Model\Lesson\Entity\TeacherGoal\TeacherGoalRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use DomainException;
use Throwable;

class Handler
{
    /**
     * @var TeacherRepository
     */
    private TeacherRepository $teachers;

    /**
     * @var GoalRepository
     */
    private GoalRepository $goals;

    /**
     * @var TeacherGoalRepository
     */
    private TeacherGoalRepository $teachersGoals;

    /**
     * @var Flusher
     */
    private Flusher $flusher;

    /**
     * @param TeacherRepository     $teachers
     * @param GoalRepository        $goals
     * @param TeacherGoalRepository $teachersGoals
     * @param Flusher               $flusher
     */
    public function __construct(TeacherRepository $teachers, GoalRepository $goals, TeacherGoalRepository $teachersGoals, Flusher $flusher)
    {
        $this->teachers      = $teachers;
        $this->goals         = $goals;
        $this->teachersGoals = $teachersGoals;
        $this->flusher       = $flusher;
    }

    /**
     * @param Command $command
     *
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function handle(Command $command): void
    {
        try {
            $goal    = $this->goals->get(new GoalId($command->goalId));
            $teacher = $this->teachers->get(new TeacherId($command->teacherId));
        } catch (\Throwable $e) {
            throw new DomainException($e->getMessage());
        }

        if ($teacher->getStatus()->isArchived()) {
            throw new DomainException('Преподаватель находится в архиве и недоступен для изменений.');
        }

        try {
            $teacherGoal = $this->teachersGoals->getByTeacherIdAndGoalId($teacher->getId()->getValue(), $goal->getId()->getValue());
        } catch (Throwable $e) {
            throw new DomainException('Цель обучения не добавлена преподавателю.');
        }

        $this->teachersGoals->remove($teacherGoal);

        $this->flusher->flush();
    }
}

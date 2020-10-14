<?php

declare(strict_types=1);

namespace App\Model\Lesson\UseCase\Schedule\Remove;

use App\Model\Flusher;
use App\Model\Lesson\Entity\Schedule\Id;
use App\Model\Lesson\Entity\Schedule\ScheduleRepository;
use App\Model\Lesson\Entity\Teacher\Id as TeacherId;
use App\Model\Lesson\Entity\Teacher\Teacher;
use App\Model\Lesson\Entity\Teacher\TeacherRepository;
use DomainException;
use Throwable;

class Handler
{
    /**
     * @var TeacherRepository
     */
    private TeacherRepository $teachers;

    /**
     * @var ScheduleRepository
     */
    private ScheduleRepository $schedules;

    /**
     * @var Flusher
     */
    private Flusher $flusher;

    /**
     * @param TeacherRepository  $teachers
     * @param ScheduleRepository $schedules
     * @param Flusher            $flusher
     */
    public function __construct(TeacherRepository $teachers, ScheduleRepository $schedules, Flusher $flusher)
    {
        $this->teachers  = $teachers;
        $this->schedules = $schedules;
        $this->flusher   = $flusher;
    }

    /**
     * @param Command $command
     */
    public function handle(Command $command): void
    {
        try {
            $teacher = $this->getTeacher($command->teacherId);
            $schedule = $this->schedules->get(new Id($command->id));
        } catch (Throwable $e) {
            throw new DomainException($e->getMessage());
        }

        if ($schedule->getTeacher()->getId()->getValue() != $teacher->getId()->getValue()) {
            throw new DomainException('График не относится к преподавателю.');
        }

        $this->schedules->remove($schedule);
        $this->flusher->flush();
    }

    /**
     * Получение преподавателя
     *
     * @param string $teacherId
     *
     * @return Teacher
     */
    private function getTeacher(string $teacherId): Teacher
    {
        $teacher = $this->teachers->get(new TeacherId($teacherId));

        if ($teacher->getStatus()->isArchived()) {
            throw new DomainException('Преподаватель находится в архиве и недоступен для изменений.');
        }

        return $teacher;
    }
}

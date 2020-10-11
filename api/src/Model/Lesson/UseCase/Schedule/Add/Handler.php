<?php

declare(strict_types=1);

namespace App\Model\Lesson\UseCase\Schedule\Add;

use App\Model\Flusher;
use App\Model\Lesson\Entity\Schedule\Id;
use App\Model\Lesson\Entity\Schedule\Schedule;
use App\Model\Lesson\Entity\Schedule\ScheduleRepository;
use App\Model\Lesson\Entity\Teacher\Id as TeacherId;
use App\Model\Lesson\Entity\Teacher\Teacher;
use App\Model\Lesson\Entity\Teacher\TeacherRepository;
use DateTimeImmutable;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use DomainException;
use Exception;

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
     *
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function handle(Command $command): void
    {
        $teacher = $this->getTeacher($command->teacherId);

        $date = $this->getDate($command->date);

        if ($this->schedules->hasByTeacherIdAndDate($command->teacherId, $date->format('Y-m-d H:i:s'))) {
            throw new DomainException('График уже добавлен преподавателю.');
        }

        $schedule = new Schedule(Id::next(), $teacher, $date, new DateTimeImmutable());

        $this->schedules->add($schedule);

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
        try {
            $teacher = $this->teachers->get(new TeacherId($teacherId));
        } catch (\Throwable $e) {
            throw new DomainException($e->getMessage());
        }

        if ($teacher->getStatus()->isArchived()) {
            throw new DomainException('Преподаватель находится в архиве и недоступен для изменений.');
        }

        return $teacher;
    }

    /**
     * Получение даты графика
     *
     * @param string $date
     *
     * @return DateTimeImmutable
     * @throws Exception
     */
    private function getDate(string $date): DateTimeImmutable
    {
        $date = new DateTimeImmutable($date);
        $date = $date->setTime((int) $date->format('H'), (int) $date->format('i'), 0);

        $now = new DateTimeImmutable();

        if ($date < $now) {
            throw new DomainException('Попытка добавления графика "задним числом".');
        }

        return $date;
    }
}

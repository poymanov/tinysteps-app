<?php

declare(strict_types=1);

namespace App\Model\Lesson\UseCase\Lesson\Add;

use App\Model\Flusher;
use App\Model\Lesson\Entity\Lesson\Id;
use App\Model\Lesson\Entity\Lesson\Lesson;
use App\Model\Lesson\Entity\Lesson\LessonRepository;
use App\Model\Lesson\Entity\Schedule\Id as ScheduleId;
use App\Model\Lesson\Entity\Schedule\Schedule;
use App\Model\Lesson\Entity\Schedule\ScheduleRepository;
use App\Model\User\Entity\User\Id as UserId;
use App\Model\User\Entity\User\UserRepository;
use DateTimeImmutable;
use DomainException;
use Exception;
use Throwable;

class Handler
{
    /** @var UserRepository */
    private UserRepository $users;

    /** @var Flusher */
    private Flusher $flusher;

    /** @var ScheduleRepository */
    private ScheduleRepository $schedules;

    /** @var LessonRepository */
    private LessonRepository $lessons;

    /**
     * @param UserRepository     $users
     * @param Flusher            $flusher
     * @param ScheduleRepository $schedules
     * @param LessonRepository   $lessons
     */
    public function __construct(UserRepository $users, Flusher $flusher, ScheduleRepository $schedules, LessonRepository $lessons)
    {
        $this->users     = $users;
        $this->flusher   = $flusher;
        $this->schedules = $schedules;
        $this->lessons   = $lessons;
    }

    /**
     * @param Command $command
     *
     * @throws Exception
     */
    public function handle(Command $command): void
    {
        $this->validateUser($command->userId);

        $schedule = $this->getSchedule($command->scheduleId);

        $lesson = new Lesson(
            Id::next(),
            $command->userId,
            $schedule,
            new DateTimeImmutable()
        );

        $this->lessons->add($lesson);

        $this->flusher->flush();
    }

    /**
     * Проверка пользователя, который хочет записаться на урок
     *
     * @param string $userId Идентификатор пользователя, который хочет записаться на урок
     */
    private function validateUser(string $userId): void
    {
        try {
            $user = $this->users->get(new UserId($userId));

            if (!$user->getStatus()->isActive()) {
                throw new Exception('Пользователь не активен.');
            }
        } catch (Throwable $exception) {
            throw new DomainException($exception->getMessage());
        }
    }

    /**
     * Получение графика занятия
     *
     * @param string $scheduleId Идентификатор графика занятия
     *
     * @return Schedule
     */
    private function getSchedule(string $scheduleId): Schedule
    {
        try {
            $schedule = $this->schedules->get(new ScheduleId($scheduleId));

            if ($this->lessons->hasByScheduleId($scheduleId)) {
                throw new Exception('Запись на урок уже существует.');
            }

            $now = new DateTimeImmutable();
            $scheduleDate = $schedule->getDate();

            if ($scheduleDate < $now) {
                throw new Exception('Попытка записи на урок из прошедшего периода.');
            }

            return $schedule;
        } catch (Throwable $exception) {
            throw new DomainException($exception->getMessage());
        }
    }
}

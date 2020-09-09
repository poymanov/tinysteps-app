<?php

declare(strict_types=1);

namespace App\Model\Lesson\UseCase\Teacher\Status;

use App\Model\Flusher;
use App\Model\Lesson\Entity\Teacher\Id;
use App\Model\Lesson\Entity\Teacher\Status;
use App\Model\Lesson\Entity\Teacher\TeacherRepository;
use App\Model\Lesson\Service\ChangeTeacherStatusSender;
use App\ReadModel\User\UserFetcher;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

class Handler
{
    /**
     * @var TeacherRepository
     */
    private TeacherRepository $teachers;

    /**
     * @var UserFetcher
     */
    private UserFetcher $users;

    /**
     * @var Flusher
     */
    private Flusher $flusher;

    /**
     * @var ChangeTeacherStatusSender
     */
    private ChangeTeacherStatusSender $sender;

    /**
     * @param TeacherRepository         $teachers
     * @param UserFetcher               $users
     * @param Flusher                   $flusher
     * @param ChangeTeacherStatusSender $sender
     */
    public function __construct(TeacherRepository $teachers, UserFetcher $users, Flusher $flusher, ChangeTeacherStatusSender $sender)
    {
        $this->teachers = $teachers;
        $this->users    = $users;
        $this->flusher  = $flusher;
        $this->sender   = $sender;
    }

    /**
     * @param Command $command
     *
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function handle(Command $command): void
    {
        $status = new Status($command->status);

        $teacher = $this->teachers->get(new Id($command->id));
        $teacher->changeStatus($status);

        $this->flusher->flush();

        $user = $this->users->get($teacher->getUserId());
        $this->sender->send($user->getEmail(), $status);
    }
}

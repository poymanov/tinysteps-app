<?php

declare(strict_types=1);

namespace App\Model\Lesson\UseCase\Teacher\Create;

use App\Model\Flusher;
use App\Model\Lesson\Entity\Teacher\Alias;
use App\Model\Lesson\Entity\Teacher\Description;
use App\Model\Lesson\Entity\Teacher\Id;
use App\Model\Lesson\Entity\Teacher\Price;
use App\Model\Lesson\Entity\Teacher\Teacher;
use App\Model\Lesson\Entity\Teacher\TeacherRepository;
use App\Model\Lesson\Service\CreateTeacherSender;
use App\Model\User\Entity\User\Id as UserId;
use App\Model\User\Entity\User\UserRepository;
use Ausi\SlugGenerator\SlugGenerator;
use DateTimeImmutable;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use DomainException;

class Handler
{
    /**
     * @var TeacherRepository
     */
    private TeacherRepository $teachers;

    /**
     * @var UserRepository
     */
    private UserRepository $users;

    /**
     * @var SlugGenerator
     */
    private SlugGenerator $slugGenerator;

    /**
     * @var CreateTeacherSender
     */
    private CreateTeacherSender $sender;

    /**
     * @var Flusher
     */
    private Flusher $flusher;

    /**
     * @param TeacherRepository   $teachers
     * @param UserRepository      $users
     * @param SlugGenerator       $slugGenerator
     * @param CreateTeacherSender $sender
     * @param Flusher             $flusher
     */
    public function __construct(
        TeacherRepository $teachers,
        UserRepository $users,
        SlugGenerator $slugGenerator,
        CreateTeacherSender $sender,
        Flusher $flusher
    ) {
        $this->teachers      = $teachers;
        $this->users         = $users;
        $this->slugGenerator = $slugGenerator;
        $this->sender        = $sender;
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
        $user = $this->users->get(new UserId($command->userId));

        if (!$user->getStatus()->isActive()) {
            throw new DomainException('Пользователь не находится в активном состоянии.');
        }

        if (!$user->getRole()->isUser()) {
            throw new DomainException('Роль пользователя не подходит для назначения в преподаватели.');
        }

        if ($this->teachers->hasByUserId($command->userId)) {
            throw new DomainException('Пользователь уже назначен преподавателем.');
        }

        $alias       = new Alias($this->slugGenerator->generate($user->getName()->getFull()));
        $description = new Description($command->description);
        $price       = new Price($command->price);

        $teacher = new Teacher(
            Id::next(),
            $command->userId,
            $alias,
            $description,
            $price,
            new DateTimeImmutable()
        );

        $this->teachers->add($teacher);

        $this->flusher->flush();

        $this->sender->send($user->getEmail());
    }
}

<?php

declare(strict_types=1);

namespace App\Model\Lesson\UseCase\Teacher\Alias;

use App\Model\Flusher;
use App\Model\Lesson\Entity\Teacher\Alias;
use App\Model\Lesson\Entity\Teacher\Id;
use App\Model\Lesson\Entity\Teacher\TeacherRepository;
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
     * @var Flusher
     */
    private Flusher $flusher;

    /**
     * @param TeacherRepository $teachers
     * @param Flusher           $flusher
     */
    public function __construct(TeacherRepository $teachers, Flusher $flusher)
    {
        $this->teachers = $teachers;
        $this->flusher  = $flusher;
    }

    /**
     * @param Command $command
     *
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function handle(Command $command): void
    {
        $alias = new Alias($command->alias);

        if ($this->teachers->hasByAlias($alias)) {
            throw new DomainException('Преподаватель с таким alias уже существует.');
        }

        $teacher = $this->teachers->get(new Id($command->id));

        $teacher->changeAlias($alias);

        $this->flusher->flush();
    }
}

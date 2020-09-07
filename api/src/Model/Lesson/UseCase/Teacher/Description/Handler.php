<?php

declare(strict_types=1);

namespace App\Model\Lesson\UseCase\Teacher\Description;

use App\Model\Flusher;
use App\Model\Lesson\Entity\Teacher\Description;
use App\Model\Lesson\Entity\Teacher\Id;
use App\Model\Lesson\Entity\Teacher\TeacherRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

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
        $teacher = $this->teachers->get(new Id($command->id));

        $teacher->changeDescription(new Description($command->description));

        $this->flusher->flush();
    }
}

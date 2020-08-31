<?php

declare(strict_types=1);

namespace App\Model\Lesson\UseCase\Goal\Create;

use App\Model\Flusher;
use App\Model\Lesson\Entity\Goal\Alias;
use App\Model\Lesson\Entity\Goal\Goal;
use App\Model\Lesson\Entity\Goal\GoalRepository;
use App\Model\Lesson\Entity\Goal\Id;
use Ausi\SlugGenerator\SlugGenerator;
use DateTimeImmutable;
use DomainException;

class Handler
{
    /**
     * @var GoalRepository
     */
    private GoalRepository $goals;

    /**
     * @var SlugGenerator
     */
    private SlugGenerator $slugGenerator;

    /**
     * @var Flusher
     */
    private Flusher $flusher;

    /**
     * @param GoalRepository $goals
     * @param SlugGenerator  $slugGenerator
     * @param Flusher        $flusher
     */
    public function __construct(GoalRepository $goals, SlugGenerator $slugGenerator, Flusher $flusher)
    {
        $this->goals         = $goals;
        $this->slugGenerator = $slugGenerator;
        $this->flusher       = $flusher;
    }

    /**
     * @param Command $command
     *
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function handle(Command $command): void
    {
        $name  = $command->name;
        $alias = new Alias($this->slugGenerator->generate($name));

        if ($this->goals->hasByAlias($alias)) {
            throw new DomainException('Цель с таким наименованием уже существует.');
        }

        $lastSort = $this->goals->getLastSortValue();
        $sort     = ++$lastSort;

        $goal = new Goal(Id::next(), $alias, $name, $sort, new DateTimeImmutable());

        $this->goals->add($goal);

        $this->flusher->flush();
    }
}

<?php

declare(strict_types=1);

namespace App\Model\Lesson\UseCase\Goal\Status;

use App\Model\Flusher;
use App\Model\Lesson\Entity\Goal\GoalRepository;
use App\Model\Lesson\Entity\Goal\Id;
use App\Model\Lesson\Entity\Goal\Status;

class Handler
{
    /**
     * @var GoalRepository
     */
    private GoalRepository $goals;

    /**
     * @var Flusher
     */
    private Flusher $flusher;

    /**
     * @param GoalRepository $goals
     * @param Flusher        $flusher
     */
    public function __construct(GoalRepository $goals, Flusher $flusher)
    {
        $this->goals   = $goals;
        $this->flusher = $flusher;
    }

    /**
     * @param Command $command
     *
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function handle(Command $command): void
    {
        $status = new Status($command->status);

        $goal = $this->goals->get(new Id($command->id));

        $goal->changeStatus($status);

        $this->flusher->flush();
    }
}

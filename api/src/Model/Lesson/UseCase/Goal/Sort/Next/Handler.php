<?php

declare(strict_types=1);

namespace App\Model\Lesson\UseCase\Goal\Sort\Next;

use App\Model\Flusher;
use App\Model\Lesson\Entity\Goal\GoalRepository;
use App\Model\Lesson\Entity\Goal\Id;
use DomainException;
use Throwable;

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
     */
    public function handle(Command $command): void
    {
        $goal = $this->goals->get(new Id($command->id));

        $currentSort = $goal->getSort();
        $nextSort    = $currentSort + 1;

        try {
            $nextGoal = $this->goals->getBySort($nextSort);
        } catch (Throwable $e) {
            throw new DomainException('Перемещение невозможно. Цель является последней.');
        }

        $goal->changeSort($nextSort);
        $nextGoal->changeSort($currentSort);

        $this->flusher->flush();
    }
}

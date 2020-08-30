<?php

declare(strict_types=1);


namespace App\ReadModel\Lesson;


use App\Model\Lesson\Entity\Goal\Goal;
use App\ReadModel\NotFoundException;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;

class GoalFetcher
{
    /**
     * @var Connection;
     */
    private $connection;


    /**
     * @var EntityManagerInterface
     */
    private $repository;

    /**
     * @param Connection             $connection
     * @param EntityManagerInterface $em
     */
    public function __construct(Connection $connection, EntityManagerInterface $em)
    {
        $this->connection = $connection;
        $this->repository = $em->getRepository(Goal::class);
    }

    /**
     * @param string $id
     *
     * @return Goal
     */
    public function get(string $id): Goal
    {
        if (!$goal = $this->repository->find($id)) {
            throw new NotFoundException('Цель обучения не найдена.');
        }

        /** @var Goal $goal */
        return $goal;
    }
}

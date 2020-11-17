<?php

declare(strict_types=1);

namespace App\ReadModel\Lesson;

use App\Model\Lesson\Entity\Goal\Goal;
use App\Model\Lesson\Entity\Goal\Status;
use App\ReadModel\NotFoundException;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
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

    /**
     * Получение списка всех целей
     *
     * @return array
     */
    public function getAll(): array
    {
        $stmt = $this->getBaseListQuery()->execute();

        return $stmt->fetchAll();
    }

    /**
     * Получение списка активных целей
     *
     * @return array
     */
    public function getActive(): array
    {
        $stmt = $this->getBaseListQuery()
            ->where('status = :status')
            ->setParameter(':status', Status::active()->getValue())
            ->execute();

        return $stmt->fetchAll();
    }

    /**
     * Получение списка целей в архиве
     *
     * @return array
     */
    public function getArchived(): array
    {
        $stmt = $this->getBaseListQuery()
            ->where('status = :status')
            ->setParameter(':status', Status::archived()->getValue())
            ->execute();

        return $stmt->fetchAll();
    }

    /**
     * Базовый запрос списка всех целей
     *
     * @return QueryBuilder
     */
    private function getBaseListQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select('id', 'alias', 'name', 'status', 'sort', 'icon' ,'created_at')
            ->from('lesson_goals')
            ->orderBy('sort', 'ASC');
    }
}

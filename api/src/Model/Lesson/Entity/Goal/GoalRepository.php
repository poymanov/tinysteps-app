<?php

declare(strict_types=1);

namespace App\Model\Lesson\Entity\Goal;

use App\Model\EntityNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

class GoalRepository
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var EntityRepository
     */
    private $repo;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em   = $em;
        $this->repo = $em->getRepository(Goal::class);
    }

    /**
     * @param Id $id
     *
     * @return Goal
     */
    public function get(Id $id): Goal
    {
        if (!$goal = $this->repo->find($id->getValue())) {
            throw new EntityNotFoundException('Цель обучения не найдена.');
        }

        /** @var Goal $goal */
        return $goal;
    }

    /**
     * Получение цели обучения по порядку сортировки
     *
     * @param int $sort
     *
     * @return Goal
     */
    public function getBySort(int $sort): Goal
    {
        if (!$goal = $this->repo->findOneBy(['sort' => $sort])) {
            throw new EntityNotFoundException('Цель обучения не найдена.');
        }

        /** @var Goal $goal */
        return $goal;
    }

    /**
     * @param string $name
     *
     * @return bool
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function hasByName(string $name): bool
    {
        return $this->repo->createQueryBuilder('t')
                ->select('COUNT(t.id)')
                ->andWhere('t.name = :name')
                ->setParameter(':name', $name)
                ->getQuery()->getSingleScalarResult() > 0;
    }

    /**
     * @param Alias $alias
     *
     * @return bool
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function hasByAlias(Alias $alias): bool
    {
        return $this->repo->createQueryBuilder('t')
                ->select('COUNT(t.id)')
                ->andWhere('t.alias = :alias')
                ->setParameter(':alias', $alias->getValue())
                ->getQuery()->getSingleScalarResult() > 0;
    }

    /**
     * Получение последнего значения сортировки
     *
     * @return int
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getLastSortValue(): int
    {
        return $this->repo->createQueryBuilder('t')
                ->select('t.sort')
                ->orderBy('t.sort', 'DESC')
                ->setMaxResults(1)
                ->getQuery()->getSingleScalarResult();
    }

    /**
     * @param Goal $goal
     */
    public function add(Goal $goal): void
    {
        $this->em->persist($goal);
    }
}

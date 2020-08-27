<?php

declare(strict_types=1);

namespace App\Model\Lesson\Entity\Goal;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

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
     * @param string $alias
     *
     * @return Goal|null
     */
    public function findByAlias(string $alias): ?Goal
    {
        return $this->repo->findOneBy(['alias' => $alias]);
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

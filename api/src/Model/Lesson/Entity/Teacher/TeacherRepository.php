<?php

declare(strict_types=1);

namespace App\Model\Lesson\Entity\Teacher;

use App\Model\EntityNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

class TeacherRepository
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
        $this->repo = $em->getRepository(Teacher::class);
    }

    /**
     * @param Id $id
     *
     * @return Teacher
     */
    public function get(Id $id): Teacher
    {
        if (!$goal = $this->repo->find($id->getValue())) {
            throw new EntityNotFoundException('Преподаватель не найден.');
        }

        /** @var Teacher $goal */
        return $goal;
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
     * @param string $userId
     *
     * @return bool
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function hasByUserId(string $userId): bool
    {
        return $this->repo->createQueryBuilder('t')
                ->select('COUNT(t.id)')
                ->andWhere('t.userId = :userId')
                ->setParameter(':userId', $userId)
                ->getQuery()->getSingleScalarResult() > 0;
    }

    /**
     * @param Teacher $teacher
     */
    public function add(Teacher $teacher): void
    {
        $this->em->persist($teacher);
    }
}

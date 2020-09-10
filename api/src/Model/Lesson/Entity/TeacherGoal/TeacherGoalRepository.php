<?php

declare(strict_types=1);

namespace App\Model\Lesson\Entity\TeacherGoal;

use App\Model\EntityNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

class TeacherGoalRepository
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
        $this->repo = $em->getRepository(TeacherGoal::class);
    }

    /**
     * @param Id $id
     *
     * @return TeacherGoal
     */
    public function get(Id $id): TeacherGoal
    {
        if (!$teacherGoal = $this->repo->find($id->getValue())) {
            throw new EntityNotFoundException('Назначение цели преподавателю не найдено.');
        }

        /** @var TeacherGoal $teacherGoal */
        return $teacherGoal;
    }

    /**
     * @param string $teacherId
     * @param string $goalId
     *
     * @return TeacherGoal
     */
    public function getByTeacherIdAndGoalId(string $teacherId, string $goalId): TeacherGoal
    {
        if (!$teacherGoal = $this->repo->findOneBy(['teacherId' => $teacherId, 'goalId' => $goalId])) {
            throw new EntityNotFoundException('Назначение цели преподавателю не найдено.');
        }

        /** @var TeacherGoal $teacherGoal */
        return $teacherGoal;
    }

    /**
     * @param string $teacherId
     * @param string $goalId
     *
     * @return bool
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function hasByTeacherIdGoalId(string $teacherId, string $goalId): bool
    {
        return $this->repo->createQueryBuilder('t')
                ->select('COUNT(t.id)')
                ->andWhere('t.teacherId = :teacherId')
                ->andWhere('t.goalId = :goalId')
                ->setParameter(':teacherId', $teacherId)
                ->setParameter(':goalId', $goalId)
                ->getQuery()->getSingleScalarResult() > 0;
    }

    /**
     * @param TeacherGoal $teacherGoal
     */
    public function add(TeacherGoal $teacherGoal): void
    {
        $this->em->persist($teacherGoal);
    }

    /**
     * @param TeacherGoal $teacherGoal
     */
    public function remove(TeacherGoal $teacherGoal): void
    {
        $this->em->remove($teacherGoal);
    }
}

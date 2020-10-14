<?php

declare(strict_types=1);

namespace App\Model\Lesson\Entity\Schedule;

use App\Model\EntityNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

class ScheduleRepository
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
        $this->repo = $em->getRepository(Schedule::class);
    }

    /**
     * @param Id $id
     *
     * @return Schedule
     */
    public function get(Id $id): Schedule
    {
        if (!$schedule = $this->repo->find($id->getValue())) {
            throw new EntityNotFoundException('График преподавателя не найден.');
        }

        /** @var Schedule $schedule */
        return $schedule;
    }

    /**
     * @param string $teacherId
     * @param string $date
     *
     * @return bool
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function hasByTeacherIdAndDate(string $teacherId, string $date): bool
    {
        return $this->repo->createQueryBuilder('t')
                ->select('COUNT(t.id)')
                ->innerJoin('t.teacher', 'n')
                ->andWhere('n.id = :teacherId')
                ->andWhere('t.date = :date')
                ->setParameter(':teacherId', $teacherId)
                ->setParameter(':date', $date)
                ->getQuery()->getSingleScalarResult() > 0;
    }

    /**
     * @param Schedule $schedule
     */
    public function add(Schedule $schedule): void
    {
        $this->em->persist($schedule);
    }

    /**
     * @param Schedule $schedule
     */
    public function remove(Schedule $schedule): void
    {
        $this->em->remove($schedule);
    }
}

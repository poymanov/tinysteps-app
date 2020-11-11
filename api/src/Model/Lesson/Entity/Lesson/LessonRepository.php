<?php

declare(strict_types=1);

namespace App\Model\Lesson\Entity\Lesson;

use App\Model\EntityNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

class LessonRepository
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
        $this->repo = $em->getRepository(Lesson::class);
    }

    /**
     * @param Id $id
     *
     * @return Lesson
     */
    public function get(Id $id): Lesson
    {
        if (!$schedule = $this->repo->find($id->getValue())) {
            throw new EntityNotFoundException('Запись на урок не найдена.');
        }

        /** @var Lesson $schedule */
        return $schedule;
    }

    /**
     * @param string $scheduleId
     *
     * @return bool
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function hasByScheduleId(string $scheduleId): bool
    {
        return $this->repo->createQueryBuilder('t')
                ->select('COUNT(t.id)')
                ->innerJoin('t.schedule', 'n')
                ->andWhere('n.id = :scheduleId')
                ->setParameter(':scheduleId', $scheduleId)
                ->getQuery()->getSingleScalarResult() > 0;
    }

    /**
     * @param Lesson $lesson
     */
    public function add(Lesson $lesson): void
    {
        $this->em->persist($lesson);
    }

    /**
     * @param Lesson $lesson
     */
    public function remove(Lesson $lesson): void
    {
        $this->em->remove($lesson);
    }
}

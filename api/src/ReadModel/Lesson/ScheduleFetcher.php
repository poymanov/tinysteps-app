<?php

declare(strict_types=1);

namespace App\ReadModel\Lesson;

use App\Model\Lesson\Entity\Schedule\Schedule;
use App\Model\Lesson\Entity\Teacher\Id;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;

class ScheduleFetcher
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
        $this->repository = $em->getRepository(Schedule::class);
    }

    /**
     * @param Id $id
     *
     * @return array
     */
    public function getAllByTeacher(Id $id): array
    {
        $query = $this->connection->createQueryBuilder()
            ->select(
                'id',
                'date'
            )
            ->from('lesson_schedules')
            ->where('teacher_id = :teacher_id')
            ->setParameter(':teacher_id', $id->getValue())
            ->orderBy('date')->execute();

        return $query->fetchAll();
    }
}

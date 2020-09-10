<?php

declare(strict_types=1);

namespace App\ReadModel\Lesson;

use App\Model\Lesson\Entity\Teacher\Id;
use App\Model\Lesson\Entity\TeacherGoal\TeacherGoal;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;

class TeacherGoalFetcher
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
        $this->repository = $em->getRepository(TeacherGoal::class);
    }

    /**
     * @param Id $id
     *
     * @return array
     */
    public function getAllGoalsByTeacher(Id $id): array
    {
        $query = $this->connection->createQueryBuilder()
            ->select(
                'lg.id',
                'lg.name'
            )
            ->from('lesson_teachers_goals', 'ltg')
            ->innerJoin('ltg', 'lesson_goals', 'lg', 'ltg.goal_id = lg.id')
            ->where('teacher_id = :teacher_id')
            ->setParameter(':teacher_id', $id->getValue())
            ->orderBy('ltg.id')->execute();

        return $query->fetchAll();
    }
}

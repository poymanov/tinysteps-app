<?php

declare(strict_types=1);


namespace App\ReadModel\Lesson;


use App\Model\Lesson\Entity\Teacher\Teacher;
use App\ReadModel\NotFoundException;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;

class TeacherFetcher
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
        $this->repository = $em->getRepository(Teacher::class);
    }

    /**
     * @param string $id
     *
     * @return Teacher
     */
    public function get(string $id): Teacher
    {
        if (!$teacher = $this->repository->find($id)) {
            throw new NotFoundException('Преподаватель не найден.');
        }

        /** @var Teacher $teacher */
        return $teacher;
    }
}

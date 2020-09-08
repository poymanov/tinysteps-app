<?php

declare(strict_types=1);


namespace App\ReadModel\Lesson;


use App\Model\Lesson\Entity\Teacher\Status;
use App\Model\Lesson\Entity\Teacher\Teacher;
use App\ReadModel\NotFoundException;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
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

    /**
     * Получение списка всех преподавателей
     *
     * @return array
     */
    public function getAll(): array
    {
        $stmt = $this->getBaseListQuery()->execute();

        return $stmt->fetchAll();
    }

    /**
     * Получение списка активных преподавателей
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
     * Получение преподавателей в архиве
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
     * Базовый запрос списка всех преподавателей
     *
     * @return QueryBuilder
     */
    private function getBaseListQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select('id', 'user_id', 'alias', 'description', 'price', 'rating', 'status', 'created_at')
            ->from('lesson_teachers')
            ->orderBy('id', 'ASC');
    }
}

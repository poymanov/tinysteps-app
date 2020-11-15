<?php

declare(strict_types=1);


namespace App\ReadModel\Lesson;


use App\Model\Lesson\Entity\Teacher\Status;
use App\Model\Lesson\Entity\Teacher\Teacher;
use App\Model\Lesson\Service\TeacherResponseFormatter;
use App\ReadModel\NotFoundException;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

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

    /** @var TeacherResponseFormatter */
    private TeacherResponseFormatter $responseFormatter;

    /**
     * @param Connection               $connection
     * @param TeacherResponseFormatter $responseFormatter
     * @param EntityManagerInterface   $em
     */
    public function __construct(
        Connection $connection,
        TeacherResponseFormatter $responseFormatter,
        EntityManagerInterface $em
    ) {
        $this->connection = $connection;
        $this->responseFormatter = $responseFormatter;
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
     * @param string $id
     *
     * @return array
     * @throws Exception
     */
    public function getOne(string $id): array
    {
        $stmt = $this->getBaseQuery()
            ->where('lt.id = :id')
            ->setParameter(':id', $id)->execute();

        $stmt->setFetchMode(FetchMode::CUSTOM_OBJECT, TeacherView::class);
        $teacher = $stmt->fetch();

        return $this->responseFormatter->full($teacher);
    }

    /**
     * Получение списка всех преподавателей
     *
     * @return array
     * @throws Exception
     */
    public function getAll(): array
    {
        $stmt = $this->getBaseListQuery()->execute();
        $stmt->setFetchMode(FetchMode::CUSTOM_OBJECT, TeacherView::class);
        $teachers = $stmt->fetchAll();

        return $this->responseFormatter->fullList($teachers);
    }

    /**
     * Получение списка активных преподавателей
     *
     * @return array
     * @throws Exception
     */
    public function getActive(): array
    {
        $stmt = $this->getBaseListQuery(Status::active())->execute();
        $stmt->setFetchMode(FetchMode::CUSTOM_OBJECT, TeacherView::class);
        $teachers = $stmt->fetchAll();

        return $this->responseFormatter->fullList($teachers);
    }

    /**
     * Получение преподавателей в архиве
     *
     * @return array
     * @throws Exception
     */
    public function getArchived(): array
    {
        $stmt = $this->getBaseListQuery(Status::archived())->execute();

        $stmt->setFetchMode(FetchMode::CUSTOM_OBJECT, TeacherView::class);
        $teachers = $stmt->fetchAll();

        return $this->responseFormatter->fullList($teachers);
    }

    /**
     * Базовый запрос списка всех преподавателей
     *
     * @param Status|null $status
     *
     * @return QueryBuilder
     */
    private function getBaseListQuery(?Status $status = null): QueryBuilder
    {
        $stmt = $this->getBaseQuery();

        if ($status) {
            $stmt->where('lt.status = :status')->setParameter(':status', $status->getValue());
        }

        $stmt->orderBy('id', 'ASC');

        return $stmt;
    }

    /**
     * Базовый запрос для преподавателей
     */
    private function getBaseQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select(
                'lt.id',
                'lt.user_id',
                'lt.alias',
                'lt.description',
                'lt.price',
                'lt.rating',
                'lt.status',
                'lt.created_at',
                'uu.name_first',
                'uu.name_last',
            )
            ->from('lesson_teachers', 'lt')
            ->innerJoin('lt', 'user_users', 'uu', 'lt.user_id = uu.id');
    }
}

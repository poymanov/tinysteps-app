<?php

declare(strict_types=1);

namespace App\ReadModel\Lesson;

use App\Model\Lesson\Entity\Teacher\Status;
use App\Model\Lesson\Entity\Teacher\Teacher;
use App\Model\Lesson\Service\TeacherResponseFormatter;
use App\ReadModel\Lesson\Helpers\TeacherFetcherQueryHelper;
use App\ReadModel\NotFoundException;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\FetchMode;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class TeacherFetcher
{
    /**
     * @var Connection;
     */
    private $connection;

    /** @var TeacherFetcherQueryHelper */
    private TeacherFetcherQueryHelper $queryHelper;

    /**
     * @var EntityManagerInterface
     */
    private $repository;

    /** @var TeacherResponseFormatter */
    private TeacherResponseFormatter $responseFormatter;

    /**
     * @param Connection                $connection
     * @param TeacherResponseFormatter  $responseFormatter
     * @param EntityManagerInterface    $em
     * @param TeacherFetcherQueryHelper $queryHelper
     */
    public function __construct(
        Connection $connection,
        TeacherResponseFormatter $responseFormatter,
        EntityManagerInterface $em,
        TeacherFetcherQueryHelper $queryHelper
    ) {
        $this->connection = $connection;
        $this->responseFormatter = $responseFormatter;
        $this->repository = $em->getRepository(Teacher::class);
        $this->queryHelper = $queryHelper;
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
        $stmt = $this->queryHelper->getBaseQuery();
        $stmt = $this->queryHelper->getWithId($stmt, $id);
        $stmt = $stmt->execute();

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
        $stmt = $this->queryHelper->getBaseQuery();
        $stmt = $this->queryHelper->getWithOrder($stmt, TeacherFetcherQueryHelper::TEACHERS_TABLE_ALIAS . '.id');
        $stmt = $stmt->execute();

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
        $stmt = $this->queryHelper->getBaseQuery();
        $stmt = $this->queryHelper->getWithStatus($stmt, Status::active());
        $stmt = $this->queryHelper->getWithOrder($stmt, TeacherFetcherQueryHelper::TEACHERS_TABLE_ALIAS . '.id');
        $stmt = $stmt->execute();

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
        $stmt = $this->queryHelper->getBaseQuery();
        $stmt = $this->queryHelper->getWithStatus($stmt, Status::archived());
        $stmt = $this->queryHelper->getWithOrder($stmt, TeacherFetcherQueryHelper::TEACHERS_TABLE_ALIAS . '.id');
        $stmt = $stmt->execute();

        $stmt->setFetchMode(FetchMode::CUSTOM_OBJECT, TeacherView::class);
        $teachers = $stmt->fetchAll();

        return $this->responseFormatter->fullList($teachers);
    }

    /**
     * Получение активных преподавателей по цели обучения
     *
     * @param string $goalId
     *
     * @return array
     * @throws Exception
     */
    public function getActiveByGoal(string $goalId): array
    {
        $stmt = $this->queryHelper->getBaseQuery();
        $stmt = $this->queryHelper->getWithStatus($stmt, Status::active());
        $stmt = $this->queryHelper->getWithGoalId($stmt, $goalId);
        $stmt = $this->queryHelper->getWithOrder($stmt, TeacherFetcherQueryHelper::TEACHERS_TABLE_ALIAS . '.id');
        $stmt = $stmt->execute();


        $stmt->setFetchMode(FetchMode::CUSTOM_OBJECT, TeacherView::class);
        $teachers = $stmt->fetchAll();

        return $this->responseFormatter->fullList($teachers);
    }
}

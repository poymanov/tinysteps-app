<?php

declare(strict_types=1);

namespace App\ReadModel\Lesson\Helpers;

use App\Model\Lesson\Entity\Teacher\Status;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

class TeacherFetcherQueryHelper
{
    const TEACHERS_TABLE_ALIAS = 'lt';
    const USERS_TABLE_ALIAS = 'uu';
    const TEACHERS_GOALS_TABLE_ALIAS = 'ltg';
    /**
     * @var Connection;
     */
    private $connection;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Базовый запрос для преподавателей
     */
    public function getBaseQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select(
                self::TEACHERS_TABLE_ALIAS . '.id',
                self::TEACHERS_TABLE_ALIAS . '.user_id',
                self::TEACHERS_TABLE_ALIAS . '.alias',
                self::TEACHERS_TABLE_ALIAS . '.description',
                self::TEACHERS_TABLE_ALIAS . '.price',
                self::TEACHERS_TABLE_ALIAS . '.rating',
                self::TEACHERS_TABLE_ALIAS . '.status',
                self::TEACHERS_TABLE_ALIAS . '.created_at',
                self::USERS_TABLE_ALIAS . '.name_first',
                self::USERS_TABLE_ALIAS . '.name_last',
            )
            ->from('lesson_teachers', self::TEACHERS_TABLE_ALIAS)
            ->innerJoin(self::TEACHERS_TABLE_ALIAS, 'user_users',
                self::USERS_TABLE_ALIAS,
                self::TEACHERS_TABLE_ALIAS . '.user_id = ' . self::USERS_TABLE_ALIAS . '.id');
    }

    /**
     * Добавление к запросу условия отбора по статусу преподавателя
     *
     * @param QueryBuilder $stmt
     * @param Status       $status
     *
     * @return QueryBuilder
     */
    public function getWithStatus(QueryBuilder $stmt, Status $status): QueryBuilder
    {
        $stmt->where(self::TEACHERS_TABLE_ALIAS . '.status = :status')
            ->setParameter(':status', $status->getValue());

        return $stmt;
    }

    /**
     * Добавление к запросу порядка сортировки
     *
     * @param QueryBuilder $stmt
     * @param string       $column
     * @param string       $order
     *
     * @return QueryBuilder
     */
    public function getWithOrder(QueryBuilder $stmt, string $column, string $order = 'ASC'): QueryBuilder
    {
        $stmt->orderBy($column, $order);

        return $stmt;
    }

    /**
     * Добавление к запросу отбора по идентификатору
     *
     * @param QueryBuilder $stmt
     * @param string       $id
     *
     * @return QueryBuilder
     */
    public function getWithId(QueryBuilder $stmt, string $id): QueryBuilder
    {
        $stmt->where(self::TEACHERS_TABLE_ALIAS . '.id = :id')
        ->setParameter(':id', $id);

        return $stmt;
    }

    /**
     * Добавление к запросу условия отбора по идентификатору цели
     *
     * @param QueryBuilder $stmt
     * @param string       $id
     *
     * @return QueryBuilder
     */
    public function getWithGoalId(QueryBuilder $stmt, string $id): QueryBuilder
    {
        $stmt->innerJoin(self::TEACHERS_TABLE_ALIAS, 'lesson_teachers_goals',
        self::TEACHERS_GOALS_TABLE_ALIAS,
        self::TEACHERS_TABLE_ALIAS . '.id = ' . self::TEACHERS_GOALS_TABLE_ALIAS . '.teacher_id')
            ->where(self::TEACHERS_GOALS_TABLE_ALIAS . '.goal_id = :goal_id')
            ->setParameter(':goal_id', $id);

        return $stmt;
    }
}

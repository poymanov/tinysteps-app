<?php

declare(strict_types=1);


namespace App\Tests\Functional\Helpers;


use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

trait DbTrait
{
    /**
     * Проверка: в таблице существует запись
     *
     * @param string $table
     * @param array  $params
     */
    public function assertIsInDatabase(string $table, array $params): void
    {
        /** @var $testCase WebTestCase */
        $testCase = $this;

        $testCase::assertTrue($this->countByParams($table, $params) > 0, 'Item not found in table ' . $table);
    }

    /**
     * Проверка: в таблице отсутствует запись
     *
     * @param string $table
     * @param array  $params
     */
    public function assertIsNotInDatabase(string $table, array $params): void
    {
        /** @var $testCase WebTestCase */
        $testCase = $this;

        $testCase::assertTrue($this->countByParams($table, $params) == 0, 'Item found in table ' . $table);
    }

    /**
     * Подсчет количества строк в таблице с учетом параметров
     *
     * @param string $table
     * @param array  $params
     *
     * @return int
     */
    private function countByParams(string $table, array $params): int
    {
        /** @var $testCase WebTestCase */
        $testCase = $this;

        /** @var $connection Connection */
        $connection = $testCase->em->getConnection();

        $qb = $connection->createQueryBuilder()
            ->select('COUNT (*)')
            ->from($table);

        foreach ($params as $param => $value) {
            if (is_null($value)) {
                $qb->andWhere("{$param} IS NULL");
            } else {
                $qb->andWhere("{$param} = :{$param}")->setParameter(":{$param}", $value);
            }
        }

        return $qb->execute()->fetchColumn();
    }
}

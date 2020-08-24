<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\ConnectionException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DbWebTestCase extends WebTestCase
{
    /**
     * @var EntityManagerInterface
     */
    public $em;

    /**
     * @var KernelBrowser
     */
    public $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
        $this->client->disableReboot();
        $this->em = static::$kernel->getContainer()->get('doctrine')->getManager();
        $this->em->getConnection()->beginTransaction();
        $this->em->getConnection()->setAutoCommit(false);
    }

    /**
     * @throws ConnectionException
     */
    protected function tearDown(): void
    {
        $this->em->getConnection()->rollBack();
        $this->em->close();
        parent::tearDown();
    }

    /**
     * Получение результата json-запроса в виде массива
     *
     * @return array
     */
    protected function getJsonData(): array
    {
        self::assertJson($content = $this->client->getResponse()->getContent());

        return json_decode($content, true);
    }

    /**
     * Post-запрос с передачей данных в json
     *
     * @param string $url
     * @param array  $data
     */
    protected function postWithContent(string $url, array $data): void
    {
        $this->client->request(
            'POST',
            $url, [], [],
            ['CONTENT_TYPE' => 'application/json'], json_encode($data));
    }

    /**
     * Проверка: в таблице существует запись
     *
     * @param string $table
     * @param array  $params
     */
    public function assertIsInDatabase(string $table, array $params): void
    {
        self::assertTrue($this->countByParams($table, $params) > 0, 'Item not found in table ' . $table);
    }

    /**
     * Проверка: в таблице отсутствует запись
     *
     * @param string $table
     * @param array  $params
     */
    public function assertIsNotInDatabase(string $table, array $params): void
    {
        self::assertTrue($this->countByParams($table, $params) == 0, 'Item found in table ' . $table);
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

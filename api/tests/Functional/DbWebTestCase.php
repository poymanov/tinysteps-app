<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Tests\Functional\Helpers\AssertionsTrait;
use App\Tests\Functional\Helpers\AuthTrait;
use App\Tests\Functional\Helpers\DbTrait;
use App\Tests\Functional\Helpers\RequestTrait;
use Doctrine\DBAL\ConnectionException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DbWebTestCase extends WebTestCase
{
    use DbTrait, AuthTrait, RequestTrait, AssertionsTrait;

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
     * Получение строки случайной длины
     *
     * @param int $length
     *
     * @return string
     */
    public function getRandomString($length = 300): string
    {
        return bin2hex(openssl_random_pseudo_bytes(intval($length / 2)));
    }
}

<?php

namespace App\Tests\Functional\Helpers;

use App\Tests\Functional\DbWebTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

trait RequestTrait
{
    /**
     * Получение результата json-запроса в виде массива
     *
     * @param int|null $status
     *
     * @return array
     */
    public function getJsonData(int $status = null): array
    {
        /** @var $testCase WebTestCase */
        $testCase = $this;

        if ($status) {
            $testCase::assertResponseStatusCodeSame($status);
        }

        $testCase::assertJson($content = $testCase->client->getResponse()->getContent());

        return json_decode($content, true);
    }

    /**
     * Post-запрос с передачей данных в json
     *
     * @param string $url
     * @param array  $data
     */
    public function postWithContent(string $url, array $data): void
    {
        $this->requestWithContent(Request::METHOD_POST, $url, $data);
    }

    /**
     * Patch-запрос с передачей данных в json
     *
     * @param string $url
     * @param array  $data
     */
    public function patchWithContent(string $url, array $data): void
    {
        $this->requestWithContent(Request::METHOD_PATCH, $url, $data);
    }

    /**
     * Запрос с передачей данных в json
     *
     * @param string $method
     * @param string $url
     * @param array  $data
     */
    protected function requestWithContent(string $method, string $url, array $data): void
    {
        /** @var $testCase DbWebTestCase */
        $testCase = $this;

        $testCase->client->request(
            $method,
            $url, [], [],
            ['CONTENT_TYPE' => 'application/json'], json_encode($data));
    }
}

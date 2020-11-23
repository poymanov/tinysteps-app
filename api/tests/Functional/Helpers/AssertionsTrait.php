<?php

declare(strict_types=1);

namespace App\Tests\Functional\Helpers;

use App\Tests\Functional\DbWebTestCase;
use Symfony\Component\HttpFoundation\Response;

trait AssertionsTrait
{
    /**
     * Проверка на неподдерживаемый метод запроса
     *
     * @param string $method
     * @param string $url
     */
    public function assertInvalidMethod(string $method, string $url): void
    {
        /** @var $testCase DbWebTestCase */
        $testCase = $this;

        $testCase->client->request($method, $url);
        self::assertResponseStatusCodeSame(Response::HTTP_METHOD_NOT_ALLOWED);
    }

    /**
     * Попытка выполнения запроса без аутентификации
     *
     * @param string $method
     * @param string $url
     */
    public function assertNotAuth(string $method, string $url): void
    {
        /** @var $testCase DbWebTestCase */
        $testCase = $this;

        $testCase->client->request($method, $url);

        self::assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Попытка выполнения запроса пользователем без прав администратора
     *
     * @param string $method
     * @param string $url
     */
    public function assertNotAdmin(string $method, string $url): void
    {
        /** @var $testCase DbWebTestCase */
        $testCase = $this;

        $testCase->authAsUser();

        $testCase->client->request($method, $url);

        $data = $testCase->getJsonData(Response::HTTP_FORBIDDEN);

        self::assertEquals([
            'message' => 'Вам запрещено выполнять данное действие',
            'errors' => [],
        ], $data);
    }

    /**
     * Обращение к адресу с uuid в неправильном формате
     *
     * @param string $method
     * @param string $url
     */
    public function assertNotValidUuid(string $method, string $url): void
    {
        /** @var $testCase DbWebTestCase */
        $testCase = $this;

        $testCase->client->request($method, $url);

        $data = $testCase->getJsonData(Response::HTTP_INTERNAL_SERVER_ERROR);

        self::assertEquals([
            'message' => 'Ошибка запроса к базе данных',
            'errors' => [],
        ], $data);
    }

    /**
     * Попытка запроса несуществующего объекта
     *
     * @param string $method
     * @param string $url
     */
    public function assertNotFound(string $method, string $url): void
    {
        /** @var $testCase DbWebTestCase */
        $testCase = $this;

        $testCase->client->request($method, $url);

        self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    /**
     * Проверка на наличие ошибок валидации в ответе запроса
     *
     * @param string $method
     * @param string $url
     * @param array  $content
     * @param array  $errors
     */
    public function assertValidationFailed(string $method, string $url, array $content, array $errors): void
    {
        /** @var $testCase DbWebTestCase */
        $testCase = $this;

        $testCase->authAsAdmin();

        $testCase->requestWithContent($method, $url, $content);

        $data = $testCase->getJsonData(Response::HTTP_UNPROCESSABLE_ENTITY);

        self::assertEquals([
            'message' => 'Ошибки валидации',
            'errors'  => $errors,
        ], $data);
    }

    /**
     * Проверка на наличие прочих ошибок в ответе запроса
     *
     * @param string $method
     * @param string $url
     * @param array  $content
     * @param string $message
     */
    public function assertBadRequest(string $method, string $url, array $content, string $message)
    {
        /** @var $testCase DbWebTestCase */
        $testCase = $this;

        $testCase->authAsAdmin();

        $testCase->requestWithContent($method, $url, $content);

        $data = $testCase->getJsonData(Response::HTTP_BAD_REQUEST);

        self::assertEquals([
            'message' => $message,
            'errors' => [],
        ], $data);
    }
}

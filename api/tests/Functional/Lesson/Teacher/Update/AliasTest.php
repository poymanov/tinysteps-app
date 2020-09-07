<?php

declare(strict_types=1);

namespace App\Tests\Functional\Lesson\Teacher\Update;

use App\Tests\Fixtures\TeacherFixture;
use App\Tests\Functional\DbWebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AliasTest extends DbWebTestCase
{
    private const BASE_URL = '/teachers/update/alias/';

    private const BASE_URL_TEACHER_1 = self::BASE_URL . TeacherFixture::TEACHER_1_ID;

    private const BASE_URL_TEACHER_2 = self::BASE_URL . TeacherFixture::TEACHER_2_ID;

    private const BASE_METHOD = Request::METHOD_PATCH;

    /**
     * Попытка GET-запроса
     */
    public function testInvalidMethod(): void
    {
        $this->assertInvalidMethod(Request::METHOD_GET, self::BASE_URL_TEACHER_1);
    }

    /**
     * Попытка выполнения запроса без аутентификации
     */
    public function testNotAuth(): void
    {
        $this->assertNotAuth(self::BASE_METHOD, self::BASE_URL_TEACHER_1);
    }

    /**
     * Попытка выполнения запроса пользователем без прав администратора
     */
    public function testNotAdmin(): void
    {
        $this->assertNotAdmin(self::BASE_METHOD, self::BASE_URL_TEACHER_1);
    }

    /**
     * Изменение alias преподавателя c uuid в неправильном формате
     */
    public function testNotValidUuid(): void
    {
        $this->assertNotValidUuid(self::BASE_METHOD, self::BASE_URL . '123');
    }

    /**
     * Попытка изменения alias несуществующего преподавателя
     */
    public function testNotFound(): void
    {
        $this->assertNotFound(self::BASE_METHOD, self::BASE_URL . '00000000-0000-0000-0000-000000000099');
    }

    /**
     * Значение нового alias не указано
     */
    public function testEmpty(): void
    {
        $this->assertValidationFailed(
            self::BASE_METHOD,
            self::BASE_URL_TEACHER_1,
            [],
            ['alias' => ['Значение не должно быть пустым.']]
        );
    }

    /**
     * Значение нового alias слишком длинное
     */
    public function testTooLongAlias(): void
    {
        $this->assertValidationFailed(
            self::BASE_METHOD,
            self::BASE_URL_TEACHER_1,
            $this->getTooLongAliasData(),
            ['alias' => ['Значение слишком длинное. Должно быть равно 255 символам или меньше.']]
        );
    }

    /**
     * Значение нового alias некорректное
     */
    public function testNotValid(): void
    {
        $this->assertBadRequest(
            self::BASE_METHOD,
            self::BASE_URL_TEACHER_1,
            $this->getNotValidData(),
            'Неправильный формат alias.'
        );
    }

    /**
     * Преподаватель с указанным alias уже существует
     */
    public function testExisted(): void
    {
        $this->assertBadRequest(
            self::BASE_METHOD,
            self::BASE_URL_TEACHER_2,
            $this->getExistedData(),
            'Преподаватель с таким alias уже существует.'
        );
    }

    /**
     * Попытка изменения alias для преподавателя, находящегося в архивном состоянии
     */
    public function testTeacherArchived(): void
    {
        $this->assertBadRequest(
            self::BASE_METHOD,
            self::BASE_URL_TEACHER_2,
            $this->getSuccessData(),
            'Преподаватель находится в архиве и недоступен для изменений.'
        );
    }

    /**
     * Успешное изменение alias
     */
    public function testSuccess(): void
    {
        $this->authAsAdmin();

        $this->patchWithContent(self::BASE_URL_TEACHER_1, $this->getSuccessData());

        $data = $this->getJsonData(Response::HTTP_OK);

        self::assertEmpty($data);

        $this->assertIsInDatabase('lesson_teachers', [
            'id'    => TeacherFixture::TEACHER_1_ID,
            'alias' => 'test-test',
        ]);
    }

    /**
     * Данные с длинным alias
     */
    public function getTooLongAliasData(): array
    {
        return [
            'alias' => $this->getRandomString(355),
        ];
    }

    /**
     * Данные для некорректного alias
     */
    public function getNotValidData(): array
    {
        return [
            'alias' => 'Test Test',
        ];
    }

    /**
     * Данные для существующего alias
     */
    public function getExistedData(): array
    {
        return [
            'alias' => 'already-request-user',
        ];
    }

    /**
     * Данные для успешного изменения alias
     */
    public function getSuccessData(): array
    {
        return [
            'alias' => 'test-test',
        ];
    }
}

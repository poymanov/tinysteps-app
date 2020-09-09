<?php

declare(strict_types=1);


namespace App\Tests\Functional\Lesson\Teacher\Goal;

use App\Tests\Fixtures\GoalFixture;
use App\Tests\Fixtures\TeacherFixture;
use App\Tests\Functional\DbWebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AddTest extends DbWebTestCase
{
    private const BASE_URL = '/teachers/goal/add/';

    private const BASE_URL_TEACHER_1 = self::BASE_URL . TeacherFixture::TEACHER_1_ID;

    private const BASE_URL_TEACHER_2 = self::BASE_URL . TeacherFixture::TEACHER_2_ID;

    private const BASE_METHOD = Request::METHOD_POST;

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
     * ID преподавателя указан в неправильном формате
     */
    public function testNotValidTeacherUuid(): void
    {
        $this->assertNotValidUuid(self::BASE_METHOD, self::BASE_URL . '123');
    }

    /**
     * Указанный преподаватель не существует
     */
    public function testTeacherNotFound(): void
    {
        $this->assertNotFound(self::BASE_METHOD, self::BASE_URL . '00000000-0000-0000-0000-000000000099');
    }

    /**
     * ID цели обучения не заполнен
     */
    public function testEmptyGoalId(): void
    {
        $this->assertValidationFailed(
            self::BASE_METHOD,
            self::BASE_URL_TEACHER_1,
            [],
            ['goalId' => ['Значение не должно быть пустым.']]
        );
    }

    /**
     * ID цели обучения не заполнен
     */
    public function testNotValidGoalId(): void
    {
        $this->assertValidationFailed(
            self::BASE_METHOD,
            self::BASE_URL_TEACHER_1,
            $this->getNotValidGoalIdData(),
            ['goalId' => ['Значение не соответствует формату UUID.']]
        );
    }

    /**
     * Указанная цель обучения не существует
     */
    public function testNotExistedGoal(): void
    {
        $this->assertBadRequest(
            self::BASE_METHOD,
            self::BASE_URL_TEACHER_1,
            $this->getNotExistedGoalData(),
            'Цель обучения не найдена.'
        );
    }

    /**
     * Попытка добавления цели обучения для преподавателя, находящегося в архивном состоянии
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
     * Попытка добавления цели обучения, находящейся в архивном состоянии
     */
    public function testGoalArchived(): void
    {
        $this->assertBadRequest(
            self::BASE_METHOD,
            self::BASE_URL_TEACHER_1,
            $this->getArchivedGoalData(),
            'Цель обучения находится в архиве и не может быть добавлена преподавателю.'
        );
    }

    /**
     * Преподавателю уже добавлена указанная цель обучения
     */
    public function testGoalAdded(): void
    {
        $this->assertBadRequest(
            self::BASE_METHOD,
            self::BASE_URL_TEACHER_1,
            $this->getGoalAddedData(),
            'Цель обучения уже добавлена преподавателю.'
        );
    }

    /**
     * Успешное добавление цели обучения преподавателю
     */
    public function testSuccess(): void
    {
        $this->authAsAdmin();

        $this->postWithContent(self::BASE_URL_TEACHER_1, $this->getSuccessData());

        $data = $this->getJsonData(Response::HTTP_CREATED);

        self::assertEmpty($data);

        $this->assertIsInDatabase('lesson_teachers_goals', [
            'teacher_id' => TeacherFixture::TEACHER_1_ID,
            'goal_id'    => GoalFixture::GOAL_1_ID,
        ]);
    }

    /**
     * Данные для цели обучения с ID в неправильном формате
     *
     * @return string[]
     */
    public function getNotValidGoalIdData(): array
    {
        return [
            'goal_id' => '123',
        ];
    }

    /**
     * Данные для несуществующей цели обучения
     *
     * @return string[]
     */
    public function getNotExistedGoalData(): array
    {
        return [
            'goal_id' => '00000000-0000-0000-0000-000000000099',
        ];
    }

    /**
     * Данные для цели обучения, находящейся в архиве
     *
     * @return array
     */
    public function getArchivedGoalData(): array
    {
        return [
            'goal_id' => GoalFixture::GOAL_5_ID,
        ];
    }

    /**
     * Данные для цели обучения, уже добавлена преподавателю
     *
     * @return array
     */
    public function getGoalAddedData(): array
    {
        return [
            'goal_id' => GoalFixture::GOAL_2_ID,
        ];
    }

    /**
     * Данные для успешного добавления цели обучения преподавателю
     *
     * @return array
     */
    public function getSuccessData(): array
    {
        return [
            'goal_id' => GoalFixture::GOAL_1_ID,
        ];
    }
}

<?php

declare(strict_types=1);

namespace App\Controller;

use App\Exception\ValidationException;
use App\Serializer\ValidationSerializer;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Tinysteps API",
 *     description="HTTP JSON API",
 * ),
 * @OA\Server(
 *     url="/"
 * ),
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 * ),
 * @OA\Tag(
 *     name="api",
 *     description="Основное",
 * ),
 * @OA\Tag(
 *     name="auth",
 *     description="Авторизация учетных записей",
 * ),
 * @OA\Tag(
 *     name="profile",
 *     description="Профиль пользователя",
 * ),
 * @OA\Tag(
 *     name="goals",
 *     description="Цели обучения",
 * ),
 * @OA\Tag(
 *     name="teachers",
 *     description="Преподаватели",
 * ),
 * @OA\Schema(
 *     schema="SuccessResponse",
 *     title="Успешное выполнение запроса",
 *     type="object",
 *     @OA\Property(property="message", type="string"),
 * ),
 * @OA\Schema(
 *     schema="AuthTokenRequest",
 *     title="Запрос аутентификационного токена",
 *     required={"grant_type", "client_id", "client_secret", "username", "password"},
 *     @OA\Property(property="grant_type", type="string", example="password", description="Тип получаемого токена"),
 *     @OA\Property(property="client_id", type="string", example="oauth", description="Название клиентского приложения"),
 *     @OA\Property(property="client_secret", type="string", example="secret", description="Ключ клиентского приложения"),
 *     @OA\Property(property="username", type="string", example="user@app.test", description="Email пользователя"),
 *     @OA\Property(property="password", type="string", example="123qwe", description="Пароль пользователя", minLength=6),
 * )
 * @OA\Schema(
 *     schema="AuthTokenResponse",
 *     title="Успешное получения токена аутентификации",
 *     type="object",
 *     @OA\Property(property="token_type", type="string", example="Bearer"),
 *     @OA\Property(property="expires_in", type="string", example="3600"),
 *     @OA\Property(property="access_token", type="string", example="zjliNjA4...."),
 *     @OA\Property(property="refresh_token", type="string", example="f0e17d00..."),
 * ),
 * @OA\Schema(
 *     schema="ErrorModel",
 *     title="Ошибка запроса к серверу",
 *     type="object",
 *     @OA\Property(property="error", type="object", nullable=true,
 *         @OA\Property(property="message", type="string"),
 *     ),
 * ),
 * @OA\Schema(
 *     schema="NotGrantedErrorModel",
 *     title="Доступ только для администраторов",
 *     type="object",
 *     @OA\Property(property="error", type="object", nullable=true,
 *         @OA\Property(property="message", type="string", example="Вам запрещено выполнять данное действие"),
 *     ),
 * ),
 * @OA\Schema(
 *     schema="ErrorModelValidationFailed",
 *     title="Ошибки валидации",
 *     type="object",
 *     @OA\Property(property="message", type="string"),
 *     @OA\Property(property="errors", type="object",
 *          @OA\Property(property="field", type="array",
 *              @OA\Items(type="string", example="Значение не должно быть пустым.")
 *          ),
 *     ),
 * ),
 * @OA\Schema(
 *     schema="GoalShowResponse",
 *     title="Данные по цели обучения",
 *     type="object",
 *     @OA\Property(property="id", type="string", example="00000000-0000-0000-0000-000000000001"),
 *     @OA\Property(property="alias", type="string", example="user@app.test"),
 *     @OA\Property(property="name", type="string", example="Для переезда"),
 *     @OA\Property(property="status", type="string", description="Статус активности", example="active"),
 *     @OA\Property(property="sort", type="integer", description="Порядок сортировки", example="1"),
 *     @OA\Property(property="created_at", type="string", description="Дата создания", example="2020-01-02 10:00:00"),
 * ),
 * @OA\Schema(
 *     schema="TeacherShowResponse",
 *     title="Данные по преподавателю",
 *     type="object",
 *     @OA\Property(property="id", type="string", example="00000000-0000-0000-0000-000000000001"),
 *     @OA\Property(property="user_id", type="string", description="Идентификатор пользователя, которого назначили преподавателем", example="00000000-0000-0000-0000-000000000001"),
 *     @OA\Property(property="alias", type="string", example="existing-user"),
 *     @OA\Property(property="name", type="object",
 *          @OA\Property(property="first", type="string", example="First"),
 *          @OA\Property(property="last", type="string", example="Last"),
 *     ),
 *     @OA\Property(property="description", type="string", example="Text"),
 *     @OA\Property(property="price", type="integer", description="Стоимость услуг преподавателя", example="100"),
 *     @OA\Property(property="rating", type="float", description="Рейтинг преподавателя", example="4"),
 *     @OA\Property(property="status", type="string", description="Статус активности", example="active"),
 *     @OA\Property(property="created_at", type="string", description="Дата создания", example="2020-01-02 10:00:00"),
 * ),
 * @OA\Post(
 *     path="/token",
 *     tags={"auth"},
 *     description="Аутентификация пользователя",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(mediaType="multipart/form-data",
 *              @OA\Schema(ref="#/components/schemas/AuthTokenRequest")
 *         )
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="Успешный ответ",
 *         @OA\JsonContent(ref="#/components/schemas/AuthTokenResponse")
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Прочие ошибки",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorModel")
 *     ),
 * )
 */
class BaseController extends AbstractController
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var ValidationSerializer
     */
    private $validationSerializer;

    /**
     * @param SerializerInterface $serializer
     *
     * @required
     */
    public function setSerializer(SerializerInterface $serializer): void
    {
        $this->serializer = $serializer;
    }

    /**
     * @param ValidatorInterface $validator
     *
     * @required
     */
    public function setValidator(ValidatorInterface $validator): void
    {
        $this->validator = $validator;
    }

    /**
     * @param ValidationSerializer $validationSerializer
     *
     * @required
     */
    public function setValidationSerializer(ValidationSerializer $validationSerializer): void
    {
        $this->validationSerializer = $validationSerializer;
    }

    /**
     * @return SerializerInterface
     */
    public function getSerializer(): SerializerInterface
    {
        return $this->serializer;
    }

    /**
     * Валидация объекта
     *
     * @param mixed $command
     */
    public function validateCommand($command): void
    {
        $violations = $this->validator->validate($command);

        if (count($violations)) {
            $json = $this->validationSerializer->serialize($violations);

            throw new ValidationException($json);
        }
    }
}

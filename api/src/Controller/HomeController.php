<?php

declare(strict_types=1);

namespace App\Controller;

use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
class HomeController extends AbstractController
{
    /**
     * @OA\Get(
     *     path="/",
     *     tags={"api"},
     *     description="Главная страница API",
     *     @OA\Response(
     *         response="200",
     *         description="Успешный ответ",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string")
     *         )
     *     )
     * )
     *
     * @Route("", name="home", methods={"GET"})
     *
     * @return Response
     */
    public function home(): Response
    {
        return $this->json(['name' => 'JSON API']);
    }
}

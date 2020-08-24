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
 * @OA\Tag(
 *     name="api",
 *     description="Основное",
 * ),
 * @OA\Tag(
 *     name="auth",
 *     description="Авторизация учетных записей",
 * ),
 * @OA\Schema(
 *     schema="SuccessResponse",
 *     title="Успешное выполнение запроса",
 *     type="object",
 *     @OA\Property(property="message", type="string"),
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
 *     schema="ErrorModelValidationFailed",
 *     title="Ошибки валидации",
 *     type="object",
 *     @OA\Property(property="message", type="string"),
 *          @OA\Property(property="errors", type="object",
 *                  @OA\Property(property="text", type="array",
 *                      @OA\Items(type="string", example="Значение не должно быть пустым.")
 *                  ),
 *              ),
 * ),
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

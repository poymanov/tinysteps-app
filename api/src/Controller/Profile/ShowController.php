<?php

declare(strict_types=1);

namespace App\Controller\Profile;

use OpenApi\Annotations as OA;
use App\Controller\BaseController;
use App\ReadModel\User\UserFetcher;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @OA\Schema(
 *     schema="ProfileResponse",
 *     title="Профиль пользователя",
 *     type="object",
 *     @OA\Property(property="id", type="string", description="Идентификатор пользователя", example="00000000-0000-0000-0000-000000000001"),
 *     @OA\Property(property="email", type="string", description="Email пользователя", example="user@app.test"),
 *     @OA\Property(property="name", type="object",
 *          @OA\Property(property="first", type="string", description="Имя пользователя", example="First"),
 *          @OA\Property(property="last", type="string", description="Фамилия пользователя", example="Last"),
 *          @OA\Property(property="full", type="string", description="Полное имя пользователя", example="First Last"),
 *     ),
 *     @OA\Property(property="status", type="string", description="Статус активности пользователя", example="active"),
 *     @OA\Property(property="role", type="string", description="Роль пользователя", example="ROLE_USER"),
 * ),
 */
class ShowController extends BaseController
{
    /**
     * @var UserFetcher
     */
    private UserFetcher $users;

    /**
     * @param UserFetcher $users
     */
    public function __construct(UserFetcher $users)
    {
        $this->users = $users;
    }

    /**
     * @OA\Get(
     *     path="/profile/show",
     *     tags={"profile"},
     *     description="Получение профиля пользователя",
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     @OA\Response(
     *         response="200",
     *         description="Успешный ответ",
     *         @OA\JsonContent(ref="#/components/schemas/ProfileResponse")
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Неавторизованный запрос профиля",
     *     )
     * )
     *
     * @Route("/profile/show", name="profile.show", methods={"GET"})
     *
     * @return Response
     */
    public function index(): Response
    {
        return $this->json($this->users->findForProfileById($this->getUser()->getId()));
    }
}

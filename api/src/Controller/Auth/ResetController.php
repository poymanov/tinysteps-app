<?php

declare(strict_types=1);

namespace App\Controller\Auth;

use OpenApi\Annotations as OA;
use App\Controller\BaseController;
use App\Model\User\UseCase\Reset;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @OA\Schema(
 *     schema="AuthResetRequest",
 *     title="Запрос на сброс пароля пользователя",
 *     required={"email"},
 *     @OA\Property(property="email", type="string", example="test@test.ru", description="Email пользователя", maxLength=255),
 * ),
 * @OA\Schema(
 *     schema="AuthResetReset",
 *     title="Сброс пароля пользователя",
 *     required={"password"},
 *     @OA\Property(property="password", type="string", example="123qwe", description="Пароль", minLength=6),
 * )
 */
class ResetController extends BaseController
{
    /**
     * @OA\Post(
     *     path="/auth/reset",
     *     tags={"auth"},
     *     description="Запрос сброса пароля",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/AuthResetRequest")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Успешный ответ",
     *         @OA\JsonContent(ref="#/components/schemas/SuccessResponse")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Прочие ошибки",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorModel")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Ошибки валидации",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorModelValidationFailed")
     *     ),
     * )
     *
     * @Route("/auth/reset", name="auth.reset", methods={"POST"})
     *
     * @param Request               $request
     *
     * @param Reset\Request\Handler $handler
     *
     * @return Response
     */
    public function request(Request $request, Reset\Request\Handler $handler): Response
    {
        /** @var Reset\Request\Command $command */
        $command = $this->getSerializer()->deserialize($request->getContent(), Reset\Request\Command::class, 'json');

        $this->validateCommand($command);

        $handler->handle($command);

        return $this->json(['message' => 'Проверьте ваш email.'], Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *     path="/auth/reset/{token}",
     *     tags={"auth"},
     *     description="Сброс старого пароля и создание нового",
     *     @OA\Parameter(name="token", in="path", required=true, description="Токен подтверждения сброса пароля учетной записи", @OA\Schema(type="string")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/AuthResetReset")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Успешный ответ",
     *         @OA\JsonContent(ref="#/components/schemas/SuccessResponse")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Прочие ошибки",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorModel")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Ошибки валидации",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorModelValidationFailed")
     *     ),
     * )
     *
     * @Route("/auth/reset/{token}", name="auth.reset.reset")
     *
     * @param Request             $request
     * @param string              $token
     * @param Reset\Reset\Handler $handler
     *
     * @return Response
     * @throws \Exception
     */
    public function reset(Request $request, string $token, Reset\Reset\Handler $handler): Response
    {
        /** @var Reset\Reset\Command $command */
        $command = $this->getSerializer()->deserialize($request->getContent(), Reset\Reset\Command::class, 'json', [
            'object_to_populate' => new Reset\Reset\Command($token),
        ]);

        $this->validateCommand($command);

        $handler->handle($command);

        return $this->json(['message' => 'Пароль успешно изменен.'], Response::HTTP_OK);
    }
}

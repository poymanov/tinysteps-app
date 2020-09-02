<?php

declare(strict_types=1);

namespace App\Controller\Auth;

use OpenApi\Annotations as OA;
use App\Controller\BaseController;
use App\Model\User\UseCase\SignUp;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @OA\Schema(
 *     schema="AuthSignUpRequest",
 *     title="Регистрация пользователя",
 *     required={"first_name", "last_name", "email", "password", "password_confirmation"},
 *     @OA\Property(property="first_name", type="string", example="test", description="Имя пользователя", maxLength=255),
 *     @OA\Property(property="last_name", type="string", example="test", description="Фамилия пользователя", maxLength=255),
 *     @OA\Property(property="email", type="string", example="test@test.ru", description="Email пользователя", maxLength=255),
 *     @OA\Property(property="password", type="string", example="123qwe", description="Пароль", minLength=6),
 *     @OA\Property(property="password_confirmation", type="string", example="123qwe", description="Подтверждение пароля", minLength=6),
 * )
 */
class SignUpController extends BaseController
{
    /**
     * @OA\Post(
     *     path="/auth/signup",
     *     tags={"auth"},
     *     description="Регистрация пользователя",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/AuthSignUpRequest")
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="Успешный ответ",
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
     * @Route("/auth/signup", name="auth.signup", methods={"POST"})
     *
     * @param Request                $request
     *
     * @param SignUp\Request\Handler $handler
     *
     * @return Response
     * @throws NoResultException
     * @throws NonUniqueResultException
     * @throws TransportExceptionInterface
     */
    public function request(Request $request, SignUp\Request\Handler $handler): Response
    {
        /** @var SignUp\Request\Command $command */
        $command = $this->getSerializer()->deserialize($request->getContent(), SignUp\Request\Command::class, 'json');

        $this->validateCommand($command);

        $handler->handle($command);

        return $this->json([], Response::HTTP_CREATED);
    }

    /**
     * @OA\Get(
     *     path="/auth/signup/{token}",
     *     tags={"auth"},
     *     description="Подтверждение учетной записи",
     *     @OA\Parameter(name="token", in="path", required=true, description="Токен подтверждения учетной записи", @OA\Schema(type="string")),
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
     * )
     *
     * @Route("/auth/signup/{token}", name="auth.signup.confirm")
     *
     * @param string                         $token
     *
     * @param SignUp\Confirm\ByToken\Handler $handler
     *
     * @return Response
     */
    public function confirm(string $token, SignUp\Confirm\ByToken\Handler $handler): Response
    {
        $command = new SignUp\Confirm\ByToken\Command($token);

        $handler->handle($command);

        return $this->json(['message' => 'Ваш email успешно подтвержден.'], Response::HTTP_OK);
    }
}

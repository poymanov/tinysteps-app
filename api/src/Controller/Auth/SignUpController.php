<?php

declare(strict_types=1);

namespace App\Controller\Auth;

use App\Model\User\UseCase\SignUp;
use App\Serializer\ValidationSerializer;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @OA\Schema(
 *     schema="AuthSignUpRequest",
 *     title="Регистрация пользователя",
 *     required={"first_name", "last_name"},
 *     @OA\Property(property="first_name", type="string", example="test", description="Имя пользователя", maxLength=255),
 *     @OA\Property(property="last_name", type="string", example="test", description="Фамилия пользователя", maxLength=255),
 *     @OA\Property(property="email", type="string", example="test@test.ru", description="Email пользователя", maxLength=255),
 *     @OA\Property(property="password", type="string", example="123qwe", description="Пароль", minLength=6),
 *     @OA\Property(property="password_confirmation", type="string", example="123qwe", description="Подтверждение пароля", minLength=6),
 * )
 */
class SignUpController extends AbstractController
{
    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * @var ValidatorInterface
     */
    private ValidatorInterface $validator;

    /**
     * @var ValidationSerializer
     */
    private ValidationSerializer $validationSerializer;

    /**
     * @param SerializerInterface  $serializer
     * @param ValidatorInterface   $validator
     * @param ValidationSerializer $validationSerializer
     */
    public function __construct(SerializerInterface $serializer, ValidatorInterface $validator, ValidationSerializer $validationSerializer)
    {
        $this->serializer           = $serializer;
        $this->validator            = $validator;
        $this->validationSerializer = $validationSerializer;
    }

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
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function request(Request $request, SignUp\Request\Handler $handler): Response
    {
        /** @var SignUp\Request\Command $command */
        $command = $this->serializer->deserialize($request->getContent(), SignUp\Request\Command::class, 'json');

        $violations = $this->validator->validate($command);

        if (count($violations)) {
            $json = $this->validationSerializer->serialize($violations);

            return new JsonResponse($json, Response::HTTP_UNPROCESSABLE_ENTITY, [], true);
        }

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
     * @param Request $request
     * @param string  $token
     *
     * @return Response
     */
    public function confirm(Request $request, string $token, SignUp\Confirm\ByToken\Handler $handler): Response
    {
        $command = new SignUp\Confirm\ByToken\Command($token);

        $handler->handle($command);

        return $this->json(['message' => 'Ваш email успешно подтвержден.'], Response::HTTP_OK);
    }
}

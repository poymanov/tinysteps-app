<?php

declare(strict_types=1);

namespace App\Controller\Auth;

use App\Model\User\UseCase\Reset;
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
class ResetController extends AbstractController
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
        $command = $this->serializer->deserialize($request->getContent(), Reset\Request\Command::class, 'json');

        $violations = $this->validator->validate($command);

        if (count($violations)) {
            $json = $this->validationSerializer->serialize($violations);

            return new JsonResponse($json, Response::HTTP_UNPROCESSABLE_ENTITY, [], true);
        }

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

        $command = new Reset\Reset\Command($token);
        $content = json_decode($request->getContent());

        if ($content->password) {
            $command->password = $content->password;
        }

        $violations = $this->validator->validate($command);

        if (count($violations)) {
            $json = $this->validationSerializer->serialize($violations);

            return new JsonResponse($json, Response::HTTP_UNPROCESSABLE_ENTITY, [], true);
        }

        $handler->handle($command);

        return $this->json(['message' => 'Пароль успешно изменен.'], Response::HTTP_OK);
    }
}

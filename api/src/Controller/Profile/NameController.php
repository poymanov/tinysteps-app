<?php

declare(strict_types=1);

namespace App\Controller\Profile;

use App\Model\User\UseCase\Name;
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
 *     schema="ChangeNameRequest",
 *     title="Изменение имени пользователя",
 *     required={"first", "last"},
 *     @OA\Property(property="first", type="string", example="test", description="Имя пользователя", maxLength=255),
 *     @OA\Property(property="last", type="string", example="test", description="Фамилия пользователя", maxLength=255),
 * )
 */
class NameController extends AbstractController
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
     * @OA\Patch (
     *     path="/profile/name",
     *     tags={"profile"},
     *     description="Изменение имени пользователя",
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ChangeNameRequest")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Успешный ответ",
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Ошибки валидации",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorModelValidationFailed")
     *     ),
     * )
     *
     * @Route("/profile/name", name="profile.name", methods={"PATCH"})
     *
     * @param Request      $request
     *
     * @param Name\Handler $handler
     *
     * @return Response
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function change(Request $request, Name\Handler $handler): Response
    {
        /** @var Name\Command $command */
        $command = $this->serializer->deserialize($request->getContent(), Name\Command::class, 'json', [
            'object_to_populate' => new Name\Command($this->getUser()->getId()),
            'ignored_attributes' => ['id'],
        ]);

        $violations = $this->validator->validate($command);

        if (count($violations)) {
            $json = $this->validationSerializer->serialize($violations);

            return new JsonResponse($json, Response::HTTP_UNPROCESSABLE_ENTITY, [], true);
        }

        $handler->handle($command);

        return $this->json([], Response::HTTP_OK);
    }
}

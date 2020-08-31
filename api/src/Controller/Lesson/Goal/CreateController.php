<?php

declare(strict_types=1);

namespace App\Controller\Lesson\Goal;

use App\Model\Lesson\UseCase\Goal;
use App\Serializer\ValidationSerializer;
use OpenApi\Annotations as OA;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @OA\Schema(
 *     schema="GoalCreateRequest",
 *     title="Создание цели обучения",
 *     required={"name"},
 *     @OA\Property(property="name", type="string", example="Прочие потребности", description="Название цели обучения", maxLength=255),
 * ),
 */
class CreateController extends AbstractController
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
     *     path="/goals/create",
     *     tags={"goals"},
     *     description="Создание цели обучения",
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/GoalCreateRequest")
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
     *         response=401,
     *         description="Неавторизованный доступ",
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Доступ только для администраторов",
     *         @OA\JsonContent(ref="#/components/schemas/NotGrantedErrorModel")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Ошибки валидации",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorModelValidationFailed")
     *     ),
     * )
     *
     * @Route("/goals/create", name="goals.create", methods={"POST"})
     * @IsGranted("ROLE_ADMIN")
     *
     * @param Request             $request
     *
     * @param Goal\Create\Handler $handler
     *
     * @return Response
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function create(Request $request, Goal\Create\Handler $handler): Response
    {
        /** @var Goal\Create\Command $command */
        $command = $this->serializer->deserialize($request->getContent(), Goal\Create\Command::class, 'json');

        $violations = $this->validator->validate($command);

        if (count($violations)) {
            $json = $this->validationSerializer->serialize($violations);

            return new JsonResponse($json, Response::HTTP_UNPROCESSABLE_ENTITY, [], true);
        }

        $handler->handle($command);

        return $this->json([], Response::HTTP_CREATED);
    }
}

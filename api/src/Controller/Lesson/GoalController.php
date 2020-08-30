<?php

declare(strict_types=1);

namespace App\Controller\Lesson;

use App\Model\Lesson\Entity\Goal\Goal as GoalModel;
use App\Model\Lesson\Service\GoalResponseFormatter;
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
 * )
 */
class GoalController extends AbstractController
{
    /**
     * @var GoalResponseFormatter
     */
    private GoalResponseFormatter $responseFormatter;

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
     * @param GoalResponseFormatter $responseFormatter
     * @param SerializerInterface   $serializer
     * @param ValidatorInterface    $validator
     * @param ValidationSerializer  $validationSerializer
     */
    public function __construct(
        GoalResponseFormatter $responseFormatter,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        ValidationSerializer $validationSerializer
    ) {
        $this->responseFormatter    = $responseFormatter;
        $this->serializer           = $serializer;
        $this->validator            = $validator;
        $this->validationSerializer = $validationSerializer;
    }

    /**
     * @OA\Post(
     *     path="/goals",
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
     * @Route("/goals", name="goals.create", methods={"POST"})
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

    /**
     * @OA\Get(
     *     path="/goals/{id}",
     *     tags={"goals"},
     *     description="Получение цели обучения",
     *     @OA\Parameter(name="id", in="path", required=true, description="Идентификатор цели обучения", @OA\Schema(type="string")),
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     @OA\Response(
     *         response="200",
     *         description="Успешный ответ",
     *         @OA\JsonContent(ref="#/components/schemas/GoalShowResponse")
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Неавторизованный запрос профиля",
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Доступ только для администраторов",
     *         @OA\JsonContent(ref="#/components/schemas/NotGrantedErrorModel")
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="ID указан в неправильном формате",
     *     )
     * )
     *
     * @Route("/goals/{id}", name="goals.show", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     *
     * @param GoalModel $goal
     *
     * @return Response
     */
    public function show(GoalModel $goal): Response
    {
        return $this->json($this->responseFormatter->full($goal));
    }

    /**
     * @OA\Get(
     *     path="/goals/{id}/change-name",
     *     tags={"goals"},
     *     description="Изменение названия цели обучения",
     *     @OA\Parameter(name="id", in="path", required=true, description="Идентификатор цели обучения", @OA\Schema(type="string")),
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     @OA\Response(
     *         response="200",
     *         description="Успешный ответ",
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Название уже сущестует",
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Неавторизованный запрос профиля",
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
     *     @OA\Response(
     *         response="500",
     *         description="ID указан в неправильном формате",
     *     )
     * )
     *
     *
     * @Route("/goals/{id}/change-name", name="goals.change-name", methods={"PATCH"})
     * @IsGranted("ROLE_ADMIN")
     *
     * @param Request           $request
     * @param GoalModel         $goal
     *
     * @param Goal\Name\Handler $handler
     *
     * @return Response
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function changeName(Request $request, GoalModel $goal, Goal\Name\Handler $handler): Response
    {
        /** @var Goal\Name\Command $command */
        $command = $this->serializer->deserialize($request->getContent(), Goal\Name\Command::class, 'json', [
            'object_to_populate' => new Goal\Name\Command($goal->getId()->getValue())
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

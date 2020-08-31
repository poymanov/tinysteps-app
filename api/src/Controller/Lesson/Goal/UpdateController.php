<?php

declare(strict_types=1);

namespace App\Controller\Lesson\Goal;

use App\Model\Lesson\Entity\Goal\Goal as GoalModel;
use App\Model\Lesson\UseCase\Goal;
use App\Serializer\ValidationSerializer;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use OpenApi\Annotations as OA;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UpdateController extends AbstractController
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
     *     path="/goals/update/name/{id}",
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
     * @Route("/goals/update/name/{id}", name="goals.update.name", methods={"PATCH"})
     * @IsGranted("ROLE_ADMIN")
     *
     * @param Request           $request
     * @param GoalModel         $goal
     *
     * @param Goal\Name\Handler $handler
     *
     * @return Response
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function name(Request $request, GoalModel $goal, Goal\Name\Handler $handler): Response
    {
        /** @var Goal\Name\Command $command */
        $command = $this->serializer->deserialize($request->getContent(), Goal\Name\Command::class, 'json', [
            'object_to_populate' => new Goal\Name\Command($goal->getId()->getValue()),
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

    /**
     * @OA\Patch (
     *     path="/goals/update/alias/{id}",
     *     tags={"goals"},
     *     description="Изменение alias цели обучения",
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
     *         description="Alias уже сущестует",
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
     * @Route("/goals/update/alias/{id}", name="goals.update.alias", methods={"PATCH"})
     * @IsGranted("ROLE_ADMIN")
     *
     * @param Request            $request
     * @param GoalModel          $goal
     *
     * @param Goal\Alias\Handler $handler
     *
     * @return Response
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function alias(Request $request, GoalModel $goal, Goal\Alias\Handler $handler): Response
    {
        /** @var Goal\Alias\Command $command */
        $command = $this->serializer->deserialize($request->getContent(), Goal\Alias\Command::class, 'json', [
            'object_to_populate' => new Goal\Alias\Command($goal->getId()->getValue()),
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

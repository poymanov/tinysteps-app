<?php

declare(strict_types=1);

namespace App\Controller\Lesson\Goal;

use OpenApi\Annotations as OA;
use App\Controller\BaseController;
use App\Model\Lesson\Entity\Goal\Goal as GoalModel;
use App\Model\Lesson\UseCase\Goal;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @OA\Schema(
 *     schema="GoalUpdateNameRequest",
 *     title="Изменение имени цели обучения",
 *     required={"name"},
 *     @OA\Property(property="name", type="string", example="Прочие потребности", description="Название цели обучения", maxLength=255),
 * ),
 * @OA\Schema(
 *     schema="GoalUpdateAliasRequest",
 *     title="Изменение alias цели обучения",
 *     required={"alias"},
 *     @OA\Property(property="alias", type="string", example="test", description="Alias цели обучения", maxLength=255),
 * ),
 * @OA\Schema(
 *     schema="GoalUpdateStatusRequest",
 *     title="Изменение статуса цели обучения",
 *     required={"status"},
 *     @OA\Property(property="status", type="string", example="archived", description="Статус цели обучения"),
 * ),
 *
 * @IsGranted("ROLE_ADMIN")
 */
class UpdateController extends BaseController
{
    /**
     * @OA\Patch (
     *     path="/goals/update/name/{id}",
     *     tags={"goals"},
     *     description="Изменение названия цели обучения",
     *     @OA\Parameter(name="id", in="path", required=true, description="Идентификатор цели обучения", @OA\Schema(type="string")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/GoalUpdateNameRequest")
     *     ),
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
        $command = $this->getSerializer()->deserialize($request->getContent(), Goal\Name\Command::class, 'json', [
            'object_to_populate' => new Goal\Name\Command($goal->getId()->getValue()),
            'ignored_attributes' => ['id'],
        ]);

        $this->validateCommand($command);

        $handler->handle($command);

        return $this->json([], Response::HTTP_OK);
    }

    /**
     * @OA\Patch (
     *     path="/goals/update/alias/{id}",
     *     tags={"goals"},
     *     description="Изменение alias цели обучения",
     *     @OA\Parameter(name="id", in="path", required=true, description="Идентификатор цели обучения", @OA\Schema(type="string")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/GoalUpdateAliasRequest")
     *     ),
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
     *
     * @param Request            $request
     * @param GoalModel          $goal
     *
     * @param Goal\Alias\Handler $handler
     *
     * @return Response
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function alias(Request $request, GoalModel $goal, Goal\Alias\Handler $handler): Response
    {
        /** @var Goal\Alias\Command $command */
        $command = $this->getSerializer()->deserialize($request->getContent(), Goal\Alias\Command::class, 'json', [
            'object_to_populate' => new Goal\Alias\Command($goal->getId()->getValue()),
            'ignored_attributes' => ['id'],
        ]);

        $this->validateCommand($command);

        $handler->handle($command);

        return $this->json([], Response::HTTP_OK);
    }

    /**
     * @OA\Patch (
     *     path="/goals/update/status/{id}",
     *     tags={"goals"},
     *     description="Изменение статуса цели обучения",
     *     @OA\Parameter(name="id", in="path", required=true, description="Идентификатор цели обучения", @OA\Schema(type="string")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/GoalUpdateStatusRequest")
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     @OA\Response(
     *         response="200",
     *         description="Успешный ответ",
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Неизвестный статус",
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
     * @Route("/goals/update/status/{id}", name="goals.update.status", methods={"PATCH"})
     *
     * @param Request             $request
     * @param GoalModel           $goal
     *
     * @param Goal\Status\Handler $handler
     *
     * @return Response
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function status(Request $request, GoalModel $goal, Goal\Status\Handler $handler): Response
    {
        /** @var Goal\Status\Command $command */
        $command = $this->getSerializer()->deserialize($request->getContent(), Goal\Status\Command::class, 'json', [
            'object_to_populate' => new Goal\Status\Command($goal->getId()->getValue()),
            'ignored_attributes' => ['id'],
        ]);

        $this->validateCommand($command);

        $handler->handle($command);

        return $this->json([], Response::HTTP_OK);
    }
}

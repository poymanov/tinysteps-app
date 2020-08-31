<?php

declare(strict_types=1);

namespace App\Controller\Lesson\Goal;

use App\Model\Lesson\Entity\Goal\Goal;
use App\Model\Lesson\UseCase\Goal\Sort;
use OpenApi\Annotations as OA;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_ADMIN")
 */
class SortController extends AbstractController
{
    /**
     * @OA\Patch (
     *     path="/goals/sort/next/{id}",
     *     tags={"goals"},
     *     description="Изменение порядка цели обучения (перемещение вперед)",
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
     *         description="Перемещение невозможно. Цель является последней",
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
     * @Route("/goals/sort/next/{id}", name="goals.sort.next", methods={"PATCH"})
     *
     * @param Goal              $goal
     *
     * @param Sort\Next\Handler $handler
     *
     * @return Response
     */
    public function next(Goal $goal, Sort\Next\Handler $handler): Response
    {
        $command = new Sort\Next\Command($goal->getId()->getValue());

        $handler->handle($command);

        return $this->json([], Response::HTTP_OK);
    }

    /**
     * @OA\Patch (
     *     path="/goals/sort/prev/{id}",
     *     tags={"goals"},
     *     description="Изменение порядка цели обучения (перемещение назад)",
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
     *         description="Перемещение невозможно. Цель является первой",
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
     * @Route("/goals/sort/prev/{id}", name="goals.sort.prev", methods={"PATCH"})
     *
     * @param Goal              $goal
     *
     * @param Sort\Prev\Handler $handler
     *
     * @return Response
     */
    public function prev(Goal $goal, Sort\Prev\Handler $handler): Response
    {
        $command = new Sort\Prev\Command($goal->getId()->getValue());

        $handler->handle($command);

        return $this->json([], Response::HTTP_OK);
    }
}

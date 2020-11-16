<?php

declare(strict_types=1);

namespace App\Controller\Lesson\Goal\Show;

use App\Controller\BaseController;
use App\Model\Lesson\Entity\Goal\Goal;
use App\Model\Lesson\Service\GoalResponseFormatter;
use App\ReadModel\Lesson\GoalFetcher;
use OpenApi\Annotations as OA;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OneController extends BaseController
{
    /**
     * @var GoalResponseFormatter
     */
    private GoalResponseFormatter $responseFormatter;

    /**
     * @var GoalFetcher
     */
    private GoalFetcher $goals;

    /**
     * @param GoalResponseFormatter $responseFormatter
     * @param GoalFetcher           $goals
     */
    public function __construct(GoalResponseFormatter $responseFormatter, GoalFetcher $goals)
    {
        $this->responseFormatter = $responseFormatter;
        $this->goals             = $goals;
    }

    /**
     * @OA\Get(
     *     path="/goals/show/one/id/{id}",
     *     tags={"goals"},
     *     description="Получение цели обучения по идентификатору",
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
     *         description="Неавторизованный запрос",
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
     * @Route("/goals/show/one/id/{id}", name="goals.show.one.id", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     *
     * @param Goal $goal
     *
     * @return Response
     */
    public function id(Goal $goal): Response
    {
        return $this->json($this->responseFormatter->full($goal));
    }

    /**
     * @OA\Get(
     *     path="/goals/show/one/alias/{alias}",
     *     tags={"goals"},
     *     description="Получение цели обучения по alias",
     *     @OA\Parameter(name="alias", in="path", required=true, description="Alias цели обучения", @OA\Schema(type="string")),
     *     @OA\Response(
     *         response="200",
     *         description="Успешный ответ",
     *         @OA\JsonContent(ref="#/components/schemas/GoalShowResponse")
     *     ),
     * )
     *
     * @Route("/goals/show/one/alias/{alias}", name="goals.show.one.alias", methods={"GET"})
     *
     * @param Goal $goal
     *
     * @return Response
     */
    public function alias(Goal $goal): Response
    {
        return $this->json($this->responseFormatter->full($goal));
    }
}

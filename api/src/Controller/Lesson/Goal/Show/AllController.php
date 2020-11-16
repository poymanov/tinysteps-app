<?php

declare(strict_types=1);

namespace App\Controller\Lesson\Goal\Show;

use App\Controller\BaseController;
use App\Model\Lesson\Service\GoalResponseFormatter;
use App\ReadModel\Lesson\GoalFetcher;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @OA\Schema(
 *     schema="GoalListResponse",
 *     title="Список целей обучения",
 *     type="array",
 *     @OA\Items(ref="#/components/schemas/GoalShowResponse")
 * )
 */
class AllController extends BaseController
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
     *     path="/goals/show/all",
     *     tags={"goals"},
     *     description="Получение списка всех целей",
     *     @OA\Response(
     *         response="200",
     *         description="Успешный ответ",
     *         @OA\JsonContent(ref="#/components/schemas/GoalListResponse")
     *     ),
     * )
     *
     * @Route("/goals/show/all", name="goals.show.all", methods={"GET"})
     *
     * @return Response
     */
    public function all(): Response
    {
        return $this->json($this->goals->getAll());
    }

    /**
     * @OA\Get(
     *     path="/goals/show/all/active",
     *     tags={"goals"},
     *     description="Получение списка активных целей",
     *     @OA\Response(
     *         response="200",
     *         description="Успешный ответ",
     *         @OA\JsonContent(ref="#/components/schemas/GoalListResponse")
     *     ),
     * )
     *
     * @Route("/goals/show/all/active", name="goals.show.all.active", methods={"GET"})
     *
     * @return Response
     */
    public function active(): Response
    {
        return $this->json($this->goals->getActive());
    }

    /**
     * @OA\Get(
     *     path="/goals/show/all/archived",
     *     tags={"goals"},
     *     description="Получение списка целей в архиве",
     *     @OA\Response(
     *         response="200",
     *         description="Успешный ответ",
     *         @OA\JsonContent(ref="#/components/schemas/GoalListResponse")
     *     ),
     * )
     *
     * @Route("/goals/show/all/archived", name="goals.show.all.archived", methods={"GET"})
     *
     * @return Response
     */
    public function archived(): Response
    {
        return $this->json($this->goals->getArchived());
    }
}

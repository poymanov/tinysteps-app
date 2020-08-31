<?php

declare(strict_types=1);

namespace App\Controller\Lesson\Goal;

use App\Model\Lesson\Entity\Goal\Goal;
use App\Model\Lesson\Service\GoalResponseFormatter;
use OpenApi\Annotations as OA;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
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
class ShowController extends AbstractController
{
    /**
     * @var GoalResponseFormatter
     */
    private GoalResponseFormatter $responseFormatter;

    /**
     * @param GoalResponseFormatter $responseFormatter
     */
    public function __construct(GoalResponseFormatter $responseFormatter)
    {
        $this->responseFormatter = $responseFormatter;
    }

    /**
     * @OA\Get(
     *     path="/goals/show/one/{id}",
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
     * @Route("/goals/show/one/{id}", name="goals.show.one", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     *
     * @param Goal $goal
     *
     * @return Response
     */
    public function one(Goal $goal): Response
    {
        return $this->json($this->responseFormatter->full($goal));
    }
}

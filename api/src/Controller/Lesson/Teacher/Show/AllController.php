<?php

declare(strict_types=1);

namespace App\Controller\Lesson\Teacher\Show;

use App\Controller\BaseController;
use App\Model\Lesson\Entity\Goal\Goal;
use App\ReadModel\Lesson\TeacherFetcher;
use Exception;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @OA\Schema(
 *     schema="TeacherListResponse",
 *     title="Список преподавателей",
 *     type="array",
 *     @OA\Items(ref="#/components/schemas/TeacherShowResponse")
 * )
 */
class AllController extends BaseController
{
    /**
     * @var TeacherFetcher
     */
    private TeacherFetcher $teachers;

    /**
     * @param TeacherFetcher $teachers
     */
    public function __construct(TeacherFetcher $teachers)
    {
        $this->teachers = $teachers;
    }

    /**
     * @OA\Get(
     *     path="/teachers/show/all",
     *     tags={"teachers"},
     *     description="Получение списка всех преподавателей",
     *     @OA\Response(
     *         response="200",
     *         description="Успешный ответ",
     *         @OA\JsonContent(ref="#/components/schemas/TeacherListResponse")
     *     ),
     * )
     *
     * @Route("/teachers/show/all", name="teachers.show.all", methods={"GET"})
     *
     * @return Response
     * @throws Exception
     */
    public function all(): Response
    {
        return $this->json($this->teachers->getAll());
    }

    /**
     * @OA\Get(
     *     path="/teachers/show/all/active",
     *     tags={"teachers"},
     *     description="Получение списка активных преподавателей",
     *     @OA\Response(
     *         response="200",
     *         description="Успешный ответ",
     *         @OA\JsonContent(ref="#/components/schemas/TeacherListResponse")
     *     ),
     * )
     *
     * @Route("/teachers/show/all/active", name="teachers.show.all.active", methods={"GET"})
     *
     * @return Response
     * @throws Exception
     */
    public function active(): Response
    {
        return $this->json($this->teachers->getActive());
    }

    /**
     * @OA\Get(
     *     path="/teachers/show/all/archived",
     *     tags={"teachers"},
     *     description="Получение списка преподавателей в архиве",
     *     @OA\Response(
     *         response="200",
     *         description="Успешный ответ",
     *         @OA\JsonContent(ref="#/components/schemas/TeacherListResponse")
     *     ),
     * )
     *
     * @Route("/teachers/show/all/archived", name="teachers.show.all.archived", methods={"GET"})
     *
     * @return Response
     * @throws Exception
     */
    public function archived(): Response
    {
        return $this->json($this->teachers->getArchived());
    }

    /**
     * @OA\Get(
     *     path="/teachers/show/all/active/goal/{id}",
     *     @OA\Parameter(name="id", in="path", required=true, description="Идентификатор цели обучения", @OA\Schema(type="string")),
     *     tags={"teachers"},
     *     description="Получение списка активных преподавателей по идентификатору цели обучения",
     *     @OA\Response(
     *         response="200",
     *         description="Успешный ответ",
     *         @OA\JsonContent(ref="#/components/schemas/TeacherListResponse")
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="ID указан в неправильном формате",
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="По указанному ID не найдена цель обучения",
     *     )
     * )
     *
     * @Route("/teachers/show/all/active/goal/{id}", name="teachers.show.all.goal", methods={"GET"})
     *
     * @param Goal $goal
     *
     * @return Response
     * @throws Exception
     */
    public function activeByGoal(Goal $goal): Response
    {
        return $this->json($this->teachers->getActiveByGoal($goal->getId()->getValue()));
    }
}

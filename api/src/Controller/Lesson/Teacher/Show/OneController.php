<?php

declare(strict_types=1);

namespace App\Controller\Lesson\Teacher\Show;

use App\Controller\BaseController;
use App\Model\Lesson\Entity\Teacher\Teacher;
use App\ReadModel\Lesson\TeacherFetcher;
use Exception;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OneController extends BaseController
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
     *     path="/teachers/show/one/{id}",
     *     tags={"teachers"},
     *     description="Получение преподавателя",
     *     @OA\Parameter(name="id", in="path", required=true, description="Идентификатор преподавателя", @OA\Schema(type="string")),
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     @OA\Response(
     *         response="200",
     *         description="Успешный ответ",
     *         @OA\JsonContent(ref="#/components/schemas/TeacherShowResponse")
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
     * @Route("/teachers/show/one/{id}", name="teachers.show.one", methods={"GET"})
     *
     * @param Teacher $teacher
     *
     * @return Response
     * @throws Exception
     */
    public function one(Teacher $teacher): Response
    {
        return $this->json($this->teachers->getOne($teacher->getId()->getValue()));
    }
}

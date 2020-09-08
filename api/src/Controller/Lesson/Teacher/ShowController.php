<?php

declare(strict_types=1);

namespace App\Controller\Lesson\Teacher;

use App\Controller\BaseController;
use App\Model\Lesson\Entity\Teacher\Teacher;
use App\Model\Lesson\Service\TeacherResponseFormatter;
use App\ReadModel\Lesson\TeacherFetcher;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @OA\Schema(
 *     schema="TeacherShowResponse",
 *     title="Данные по преподавателю",
 *     type="object",
 *     @OA\Property(property="id", type="string", example="00000000-0000-0000-0000-000000000001"),
 *     @OA\Property(property="user_id", type="string", description="Идентификатор пользователя, которого назначили преподавателем", example="00000000-0000-0000-0000-000000000001"),
 *     @OA\Property(property="alias", type="string", example="existing-user"),
 *     @OA\Property(property="description", type="string", example="Text"),
 *     @OA\Property(property="price", type="integer", description="Стоимость услуг преподавателя", example="100"),
 *     @OA\Property(property="rating", type="float", description="Рейтинг преподавателя", example="4"),
 *     @OA\Property(property="status", type="string", description="Статус активности", example="active"),
 *     @OA\Property(property="created_at", type="string", description="Дата создания", example="2020-01-02 10:00:00"),
 * ),
 * @OA\Schema(
 *     schema="TeacherListResponse",
 *     title="Список преподавателей",
 *     type="array",
 *     @OA\Items(ref="#/components/schemas/TeacherShowResponse")
 * )
 */
class ShowController extends BaseController
{
    /**
     * @var TeacherResponseFormatter
     */
    private TeacherResponseFormatter $responseFormatter;

    /**
     * @var TeacherFetcher
     */
    private TeacherFetcher $teachers;

    /**
     * @param TeacherResponseFormatter $responseFormatter
     * @param TeacherFetcher           $teachers
     */
    public function __construct(TeacherResponseFormatter $responseFormatter, TeacherFetcher $teachers)
    {
        $this->responseFormatter = $responseFormatter;
        $this->teachers          = $teachers;
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
     */
    public function one(Teacher $teacher): Response
    {
        return $this->json($this->responseFormatter->full($teacher));
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
     */
    public function all(): Response
    {
        return $this->json($this->teachers->getAll());
    }

    /**
     * @OA\Get(
     *     path="/teachers/show/active",
     *     tags={"teachers"},
     *     description="Получение списка активных преподавателей",
     *     @OA\Response(
     *         response="200",
     *         description="Успешный ответ",
     *         @OA\JsonContent(ref="#/components/schemas/TeacherListResponse")
     *     ),
     * )
     *
     * @Route("/teachers/show/active", name="teachers.show.active", methods={"GET"})
     *
     * @return Response
     */
    public function active(): Response
    {
        return $this->json($this->teachers->getActive());
    }

    /**
     * @OA\Get(
     *     path="/teachers/show/archived",
     *     tags={"teachers"},
     *     description="Получение списка преподавателей в архиве",
     *     @OA\Response(
     *         response="200",
     *         description="Успешный ответ",
     *         @OA\JsonContent(ref="#/components/schemas/TeacherListResponse")
     *     ),
     * )
     *
     * @Route("/teachers/show/archived", name="teachers.show.archived", methods={"GET"})
     *
     * @return Response
     */
    public function archived(): Response
    {
        return $this->json($this->teachers->getArchived());
    }
}

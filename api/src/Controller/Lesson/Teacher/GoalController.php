<?php

declare(strict_types=1);

namespace App\Controller\Lesson\Teacher;

use App\Controller\BaseController;
use App\Model\Lesson\Entity\Teacher\Teacher;
use App\Model\Lesson\UseCase\TeacherGoal;
use App\ReadModel\Lesson\TeacherGoalFetcher;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use OpenApi\Annotations as OA;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class GoalController extends BaseController
{
    /**
     * @var TeacherGoalFetcher
     */
    private TeacherGoalFetcher $teachersGoals;

    /**
     * @param TeacherGoalFetcher $teachersGoals
     */
    public function __construct(TeacherGoalFetcher $teachersGoals)
    {
        $this->teachersGoals = $teachersGoals;
    }

    /**
     * @OA\Post(
     *     path="/teachers/goal/add/{id}",
     *     tags={"teachers"},
     *     description="Назначения преподавателю цели обучения",
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     @OA\Parameter(name="id", in="path", required=true, description="Идентификатор преподавателя", @OA\Schema(type="string")),
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"goal_id"},
     *              @OA\Property(property="goal_id", type="string", example="00000000-0000-0000-0000-000000000001", description="Идентификатор цели, которая назначается преподавателю"),
     *          )
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="Успешный ответ",
     *         @OA\JsonContent(ref="#/components/schemas/SuccessResponse")
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
     * @Route("/teachers/goal/add/{id}", name="teachers.goals.add", methods={"POST"})
     *
     * @IsGranted("ROLE_ADMIN")
     *
     * @param Request                 $request
     * @param Teacher                 $teacher
     * @param TeacherGoal\Add\Handler $handler
     *
     * @return JsonResponse
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function add(Request $request, Teacher $teacher, TeacherGoal\Add\Handler $handler)
    {
        /** @var TeacherGoal\Add\Command $command */
        $command = $this->getSerializer()->deserialize($request->getContent(), TeacherGoal\Add\Command::class, 'json', [
            'object_to_populate' => new TeacherGoal\Add\Command($teacher->getId()->getValue()),
            'ignored_attributes' => ['teacher_id'],
        ]);

        $this->validateCommand($command);

        $handler->handle($command);

        return $this->json([], Response::HTTP_CREATED);
    }

    /**
     * @OA\Delete(
     *     path="/teachers/goal/remove/{id}",
     *     tags={"teachers"},
     *     description="Удаление у преподавателя цели обучения",
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     @OA\Parameter(name="id", in="path", required=true, description="Идентификатор преподавателя", @OA\Schema(type="string")),
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"goal_id"},
     *              @OA\Property(property="goal_id", type="string", example="00000000-0000-0000-0000-000000000001", description="Идентификатор цели, которая удаляется у преподавателя"),
     *          )
     *     ),
     *     @OA\Response(
     *         response="204",
     *         description="Успешное выполнение удаления",
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
     * @Route("/teachers/goal/remove/{id}", name="teachers.goals.remove", methods={"DELETE"})
     *
     * @IsGranted("ROLE_ADMIN")
     *
     * @param Request                    $request
     * @param Teacher                    $teacher
     *
     * @param TeacherGoal\Remove\Handler $handler
     *
     * @return Response
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function remove(Request $request, Teacher $teacher, TeacherGoal\Remove\Handler $handler): Response
    {
        /** @var TeacherGoal\Remove\Command $command */
        $command = $this->getSerializer()->deserialize($request->getContent(), TeacherGoal\Remove\Command::class, 'json', [
            'object_to_populate' => new TeacherGoal\Remove\Command($teacher->getId()->getValue()),
            'ignored_attributes' => ['teacher_id'],
        ]);

        $this->validateCommand($command);

        $handler->handle($command);

        return $this->json([], Response::HTTP_NO_CONTENT);
    }

    /**
     * @OA\Get(
     *     path="/teachers/goal/show/all/{id}",
     *     tags={"teachers"},
     *     description="Получение списка целей обучения преподавателя",
     *     @OA\Parameter(name="id", in="path", required=true, description="Идентификатор преподавателя", @OA\Schema(type="string")),
     *     @OA\Response(
     *         response="200",
     *         description="Успешный ответ",
     *         @OA\JsonContent(
     *              @OA\Property(property="id", type="string", example="00000000-0000-0000-0000-000000000001"),
     *              @OA\Property(property="name", type="string", example="Для переезда"),
     *         )
     *     ),
     * )
     *
     * @Route("/teachers/goal/show/all/{id}", name="teachers.goals.show.all")
     *
     * @param Teacher $teacher
     *
     * @return Response
     */
    public function showAll(Teacher $teacher): Response
    {
        if ($teacher->getStatus()->isArchived()) {
            throw new NotFoundHttpException();
        }

        return $this->json($this->teachersGoals->getAllGoalsByTeacher($teacher->getId()));
    }
}

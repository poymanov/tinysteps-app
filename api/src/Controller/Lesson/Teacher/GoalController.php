<?php

declare(strict_types=1);

namespace App\Controller\Lesson\Teacher;

use App\Controller\BaseController;
use App\Model\Lesson\Entity\Teacher\Teacher;
use App\Model\Lesson\UseCase\TeacherGoal;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use OpenApi\Annotations as OA;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_ADMIN")
 */
class GoalController extends BaseController
{
    /**
     * @OA\Post(
     *     path="/teachers/goal/add/{id}",
     *     tags={"teachers"},
     *     description="Назначения преподавателю цели обучения",
     *     security={
     *         {"bearerAuth": {}}
     *     },
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
     * @Route("/teachers/goal/add/{id}", name="teachers.goal.add", methods={"POST"})
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
     * @Route("/teachers/goal/remove/{id}", name="teachers.goal.remove", methods={"DELETE"})
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
}

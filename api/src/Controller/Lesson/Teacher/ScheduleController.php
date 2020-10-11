<?php

declare(strict_types=1);

namespace App\Controller\Lesson\Teacher;

use App\Controller\BaseController;
use App\Model\Lesson\Entity\Teacher\Teacher;
use App\Model\Lesson\UseCase\Schedule;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use OpenApi\Annotations as OA;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ScheduleController extends BaseController
{
    /**
     * @OA\Post(
     *     path="/teachers/schedule/add/{id}",
     *     tags={"teachers"},
     *     description="Назначения преподавателю графика занятия",
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     @OA\Parameter(name="id", in="path", required=true, description="Идентификатор преподавателя", @OA\Schema(type="string")),
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"date"},
     *              @OA\Property(property="date", type="string", example="2020-12-12 12:23:00", description="Дата проведения урока"),
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
     * @Route("/teachers/schedule/add/{id}", name="teachers.schedules.add", methods={"POST"})
     *
     * @IsGranted("ROLE_ADMIN")
     *
     * @param Request              $request
     * @param Teacher              $teacher
     * @param Schedule\Add\Handler $handler
     *
     * @return Response
     *
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function add(Request $request, Teacher $teacher, Schedule\Add\Handler $handler): Response
    {
        /** @var Schedule\Add\Command $command */
        $command = $this->getSerializer()->deserialize($request->getContent(), Schedule\Add\Command::class, 'json', [
            'object_to_populate' => new Schedule\Add\Command($teacher->getId()->getValue()),
            'ignored_attributes' => ['teacher_id'],
        ]);

        $this->validateCommand($command);

        $handler->handle($command);

        return $this->json([], Response::HTTP_CREATED);
    }
}

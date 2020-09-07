<?php

declare(strict_types=1);

namespace App\Controller\Lesson\Teacher;

use App\Controller\BaseController;
use App\Model\Lesson\Entity\Teacher\Teacher as TeacherModel;
use App\Model\Lesson\UseCase\Teacher;
use OpenApi\Annotations as OA;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_ADMIN")
 */
class UpdateController extends BaseController
{
    /**
     * @OA\Patch (
     *     path="/teachers/update/status/{id}",
     *     tags={"teachers"},
     *     description="Изменение статуса преподавателя",
     *     @OA\Parameter(name="id", in="path", required=true, description="Идентификатор преподавателя", @OA\Schema(type="string")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="archived", description="Статус преподавателя")
     *         )
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     @OA\Response(
     *         response="200",
     *         description="Успешный ответ",
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Неизвестный статус",
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Неавторизованный запрос на изменение",
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
     *     @OA\Response(
     *         response="500",
     *         description="ID указан в неправильном формате",
     *     )
     * )
     *
     * @Route("/teachers/update/status/{id}", name="teachers.update.status", methods={"PATCH"})
     *
     * @param Request                $request
     * @param TeacherModel           $teacher
     *
     * @param Teacher\Status\Handler $handler
     *
     * @return Response
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function status(Request $request, TeacherModel $teacher, Teacher\Status\Handler $handler): Response
    {
        /** @var Teacher\Status\Command $command */
        $command = $this->getSerializer()->deserialize($request->getContent(), Teacher\Status\Command::class, 'json', [
            'object_to_populate' => new Teacher\Status\Command($teacher->getId()->getValue()),
            'ignored_attributes' => ['id'],
        ]);

        $this->validateCommand($command);

        $handler->handle($command);

        return $this->json([], Response::HTTP_OK);
    }
}

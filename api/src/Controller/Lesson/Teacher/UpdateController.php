<?php

declare(strict_types=1);

namespace App\Controller\Lesson\Teacher;

use App\Controller\BaseController;
use App\Model\Lesson\Entity\Teacher\Teacher as TeacherModel;
use App\Model\Lesson\UseCase\Teacher;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
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
     *              required={"status"},
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
     * @throws NoResultException
     * @throws NonUniqueResultException
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

    /**
     * @OA\Patch (
     *     path="/teachers/update/alias/{id}",
     *     tags={"teachers"},
     *     description="Изменение alias преподавателя",
     *     @OA\Parameter(name="id", in="path", required=true, description="Идентификатор преподавателя", @OA\Schema(type="string")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *              required={"alias"},
     *              @OA\Property(property="alias", type="string", example="test", description="Alias преподавателя", maxLength=255),
     *          )
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
     *         description="Alias уже сущестует",
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
     * @Route("/teachers/update/alias/{id}", name="teachers.update.alias", methods={"PATCH"})
     *
     * @param Request               $request
     * @param TeacherModel          $teacher
     *
     * @param Teacher\Alias\Handler $handler
     *
     * @return Response
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function alias(Request $request, TeacherModel $teacher, Teacher\Alias\Handler $handler): Response
    {
        /** @var Teacher\Alias\Command $command */
        $command = $this->getSerializer()->deserialize($request->getContent(), Teacher\Alias\Command::class, 'json', [
            'object_to_populate' => new Teacher\Alias\Command($teacher->getId()->getValue()),
            'ignored_attributes' => ['id'],
        ]);

        $this->validateCommand($command);

        $handler->handle($command);

        return $this->json([], Response::HTTP_OK);
    }

    /**
     * @OA\Patch (
     *     path="/teachers/update/description/{id}",
     *     tags={"teachers"},
     *     description="Изменение описания преподавателя",
     *     @OA\Parameter(name="id", in="path", required=true, description="Идентификатор преподавателя", @OA\Schema(type="string")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *              required={"description"},
     *              @OA\Property(property="description", type="string", example="Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient.", description="Описание преподавателя", minLength=150),
     *          )
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
     *         description="Попытка изменения описания для преподавателя, находящегося в архивном состоянии",
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
     * @Route("/teachers/update/description/{id}", name="teachers.update.description", methods={"PATCH"})
     *
     * @param Request                     $request
     * @param TeacherModel                $teacher
     *
     * @param Teacher\Description\Handler $handler
     *
     * @return Response
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function description(Request $request, TeacherModel $teacher, Teacher\Description\Handler $handler): Response
    {
        /** @var Teacher\Description\Command $command */
        $command = $this->getSerializer()->deserialize($request->getContent(), Teacher\Description\Command::class, 'json', [
            'object_to_populate' => new Teacher\Description\Command($teacher->getId()->getValue()),
            'ignored_attributes' => ['id'],
        ]);

        $this->validateCommand($command);

        $handler->handle($command);

        return $this->json([], Response::HTTP_OK);
    }

    /**
     * @OA\Patch (
     *     path="/teachers/update/price/{id}",
     *     tags={"teachers"},
     *     description="Изменение стоимости услуг преподавателя",
     *     @OA\Parameter(name="id", in="path", required=true, description="Идентификатор преподавателя", @OA\Schema(type="string")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *              required={"price"},
     *              @OA\Property(property="price", type="integer", example=100),
     *          )
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
     *         description="Попытка изменения цены для преподавателя, находящегося в архивном состоянии",
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
     * @Route("/teachers/update/price/{id}", name="teachers.update.price", methods={"PATCH"})
     *
     * @param Request               $request
     * @param TeacherModel          $teacher
     * @param Teacher\Price\Handler $handler
     *
     * @return Response
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function price(Request $request, TeacherModel $teacher, Teacher\Price\Handler $handler): Response
    {
        /** @var Teacher\Price\Command $command */
        $command = $this->getSerializer()->deserialize($request->getContent(), Teacher\Price\Command::class, 'json', [
            'object_to_populate' => new Teacher\Price\Command($teacher->getId()->getValue()),
            'ignored_attributes' => ['id'],
        ]);

        $this->validateCommand($command);

        $handler->handle($command);

        return $this->json([], Response::HTTP_OK);
    }

    /**
     * @OA\Patch (
     *     path="/teachers/update/rating/{id}",
     *     tags={"teachers"},
     *     description="Изменение рейтинга преподавателя",
     *     @OA\Parameter(name="id", in="path", required=true, description="Идентификатор преподавателя", @OA\Schema(type="string")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *              required={"rating"},
     *              @OA\Property(property="rating", type="float", example=4),
     *          )
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
     *         description="Попытка изменения рейтинга для преподавателя, находящегося в архивном состоянии",
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
     * @Route("/teachers/update/rating/{id}", name="teachers.update.rating", methods={"PATCH"})
     *
     * @param Request                $request
     * @param TeacherModel           $teacher
     * @param Teacher\Rating\Handler $handler
     *
     * @return Response
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function rating(Request $request, TeacherModel $teacher, Teacher\Rating\Handler $handler): Response
    {
        /** @var Teacher\Rating\Command $command */
        $command = $this->getSerializer()->deserialize($request->getContent(), Teacher\Rating\Command::class, 'json', [
            'object_to_populate' => new Teacher\Rating\Command($teacher->getId()->getValue()),
            'ignored_attributes' => ['id'],
        ]);

        $this->validateCommand($command);

        $handler->handle($command);

        return $this->json([], Response::HTTP_OK);
    }
}

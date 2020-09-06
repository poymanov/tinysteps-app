<?php

declare(strict_types=1);

namespace App\Controller\Lesson\Teacher;

use App\Controller\BaseController;
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
class CreateController extends BaseController
{
    /**
     * @OA\Post(
     *     path="/teachers/create",
     *     tags={"teachers"},
     *     description="Назначения пользователя преподавателем",
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"user_id", "price", "description"},
     *              @OA\Property(property="user_id", type="string", example="00000000-0000-0000-0000-000000000001", description="Идентификатор пользователя, которого назначают преподавателем"),
     *              @OA\Property(property="price", type="integer", example=100, description="Стоимость услуг преподавателя"),
     *              @OA\Property(property="description", type="string", example="Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient.", description="Стоимость услуг преподавателя", minLength=150),
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
     * @Route("/teachers/create", name="teachers.create", methods={"POST"})
     *
     * @param Request                $request
     * @param Teacher\Create\Handler $handler
     *
     * @return Response
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function create(Request $request, Teacher\Create\Handler $handler): Response
    {
        /** @var Teacher\Create\Command $command */
        $command = $this->getSerializer()->deserialize($request->getContent(), Teacher\Create\Command::class, 'json');

        $this->validateCommand($command);

        $handler->handle($command);

        return $this->json([], Response::HTTP_CREATED);
    }
}

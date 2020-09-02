<?php

declare(strict_types=1);

namespace App\Controller\Profile;

use OpenApi\Annotations as OA;
use App\Controller\BaseController;
use App\Model\User\UseCase\Name;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @OA\Schema(
 *     schema="ChangeNameRequest",
 *     title="Изменение имени пользователя",
 *     required={"first", "last"},
 *     @OA\Property(property="first", type="string", example="test", description="Имя пользователя", maxLength=255),
 *     @OA\Property(property="last", type="string", example="test", description="Фамилия пользователя", maxLength=255),
 * )
 */
class NameController extends BaseController
{
    /**
     * @OA\Patch (
     *     path="/profile/name",
     *     tags={"profile"},
     *     description="Изменение имени пользователя",
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ChangeNameRequest")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Успешный ответ",
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Ошибки валидации",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorModelValidationFailed")
     *     ),
     * )
     *
     * @Route("/profile/name", name="profile.name", methods={"PATCH"})
     *
     * @param Request      $request
     *
     * @param Name\Handler $handler
     *
     * @return Response
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function change(Request $request, Name\Handler $handler): Response
    {
        /** @var Name\Command $command */
        $command = $this->getSerializer()->deserialize($request->getContent(), Name\Command::class, 'json', [
            'object_to_populate' => new Name\Command($this->getUser()->getId()),
            'ignored_attributes' => ['id'],
        ]);

        $this->validateCommand($command);

        $handler->handle($command);

        return $this->json([], Response::HTTP_OK);
    }
}

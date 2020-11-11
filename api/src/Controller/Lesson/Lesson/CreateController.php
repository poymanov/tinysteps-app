<?php

declare(strict_types=1);

namespace App\Controller\Lesson\Lesson;

use App\Controller\BaseController;
use App\Model\Lesson\UseCase\Lesson;
use Exception;
use OpenApi\Annotations as OA;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreateController extends BaseController
{
    /**
     * @OA\Post(
     *     path="/lessons/add",
     *     tags={"lessons"},
     *     description="Запись на урок к преподавателю",
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"schedule_id"},
     *              @OA\Property(property="schedule_id", type="string", example="00000000-0000-0000-0000-000000000001", description="График проведения урока преподавателем"),
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
     *         response=422,
     *         description="Ошибки валидации",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorModelValidationFailed")
     *     ),
     * )
     *
     * @Route("/lessons/add", name="lessons.add", methods={"POST"})
     * @IsGranted("ROLE_USER")
     *
     * @param Request            $request
     *
     * @param Lesson\Add\Handler $handler
     *
     * @return Response
     * @throws Exception
     */
    public function add(Request $request, Lesson\Add\Handler $handler): Response
    {
        /** @var Lesson\Add\Command $command */
        $command = $this->getSerializer()->deserialize($request->getContent(), Lesson\Add\Command::class, 'json', [
            'object_to_populate' => new Lesson\Add\Command($this->getUser()->getId()),
            'ignored_attributes' => ['user_id'],
        ]);

        $this->validateCommand($command);

        $handler->handle($command);

        return $this->json([], Response::HTTP_CREATED);
    }
}

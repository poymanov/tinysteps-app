<?php

declare(strict_types=1);

namespace App\Controller;

use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends BaseController
{
    /**
     * @OA\Get(
     *     path="/",
     *     tags={"api"},
     *     description="Главная страница API",
     *     @OA\Response(
     *         response="200",
     *         description="Успешный ответ",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string")
     *         )
     *     )
     * )
     *
     * @Route("", name="home", methods={"GET"})
     *
     * @return Response
     */
    public function home(): Response
    {
        return $this->json(['name' => 'JSON API']);
    }
}

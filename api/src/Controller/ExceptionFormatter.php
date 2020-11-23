<?php

declare(strict_types=1);

namespace App\Controller;

use App\Exception\ValidationException;
use Doctrine\DBAL\Exception\DriverException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use UnexpectedValueException;

class ExceptionFormatter implements EventSubscriberInterface
{
    /**
     * @return array|string[]
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }

    /**
     * @param ExceptionEvent $event
     */
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof NotFoundHttpException) {
            $message = 'Неизвестный запрос';
            $event->setResponse($this->buildErrorResponse($message, Response::HTTP_NOT_FOUND));
        } elseif ($exception instanceof MethodNotAllowedHttpException) {
            $message = 'Неподдерживаемый тип запроса';
            $event->setResponse($this->buildErrorResponse($message, Response::HTTP_METHOD_NOT_ALLOWED));
        } elseif ($exception instanceof AccessDeniedHttpException) {
            $message = 'Вам запрещено выполнять данное действие';
            $event->setResponse($this->buildErrorResponse($message, Response::HTTP_FORBIDDEN));
        } elseif ($exception instanceof DriverException) {
            $message = 'Ошибка запроса к базе данных';
            $event->setResponse($this->buildErrorResponse($message, Response::HTTP_INTERNAL_SERVER_ERROR));
        } elseif ($exception instanceof UnexpectedValueException) {
            $message = 'Неверный тип одного/нескольких указанных полей';
            $event->setResponse($this->buildErrorResponse($message, Response::HTTP_BAD_REQUEST));
        } elseif ($exception instanceof ValidationException) {
            $event->setResponse($this->buildResponseFromJson($exception->getJson(), Response::HTTP_UNPROCESSABLE_ENTITY));
        } else {
            $event->setResponse($this->buildErrorResponse($exception->getMessage(), Response::HTTP_BAD_REQUEST));
        }
    }

    /**
     * Формирование json-ответа с ошибкой
     *
     * @param string $message    Описание ошибки
     * @param int    $statusCode Код статуса ответа
     *
     * @return JsonResponse
     */
    private function buildErrorResponse(string $message, int $statusCode): JsonResponse
    {
        return new JsonResponse([
            'message' => $message,
            'errors' => new \StdClass()
        ], $statusCode);
    }

    /**
     * Формирование json-ответа из готового json
     *
     * @param string $jsonContent
     * @param int    $statusCode
     *
     * @return JsonResponse
     */
    private function buildResponseFromJson(string $jsonContent, int $statusCode): JsonResponse
    {
        return new JsonResponse($jsonContent, $statusCode, [], true);
    }
}

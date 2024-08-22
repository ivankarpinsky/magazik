<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        // Создаем JSON ответ с кодом ошибки и сообщением
        $response = new JsonResponse([
            'status' => $exception instanceof HttpExceptionInterface ? $exception->getStatusCode() : 500,
            'message' => $exception->getMessage(),
        ]);

        // Устанавливаем код статуса на основе исключения
        if ($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
        } else {
            $response->setStatusCode(500);
        }

        // Устанавливаем новый ответ в событие
        $event->setResponse($response);
    }
}
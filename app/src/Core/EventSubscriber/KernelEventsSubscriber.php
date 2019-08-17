<?php

namespace App\Core\EventSubscriber;

use App\Core\Exception\ConstraintValidationException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class KernelEventsSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => [
                ['onKernelException', 30]
            ],
        ];
    }

    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getException();

        if ($exception instanceof ConstraintValidationException) {
            $errors = $exception->getErrors();
            $event->setResponse(new JsonResponse([
                'status' => 'validation-error',
                'errors' => $errors
            ], Response::HTTP_BAD_REQUEST));
        }
    }
}
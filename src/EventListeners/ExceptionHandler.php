<?php

namespace App\EventListeners;

use App\Entity\HypermidiaResponse;
use App\Helper\ResponseFactory;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExceptionHandler implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        // TODO: Implement getSubscribedEvents() method.
        return [
            KernelEvents::EXCEPTION => 'handle404Exception',
        ];
    }

    public function handle404Exception(ExceptionEvent $event)
    {
        if($event->getThrowable() instanceof NotFoundHttpException) {
            $response = HypermidiaResponse::fromError($event->getThrowable())->getResponse();
            $response->setStatusCode(404);
            $event->setResponse($response);
            /*
            //Caso queira redirecionar para outra rota caso a rota digitado pelo usuario não existe
            $event->setResponse(new RedirectResponse('http://localhost:8080/medicos'));
            */
        }
    }
}
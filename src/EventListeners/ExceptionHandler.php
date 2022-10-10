<?php

namespace App\EventListeners;

use App\Entity\HypermidiaResponse;
use App\Helper\EntityFactoryException;
use App\Helper\ResponseFactory;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExceptionHandler implements EventSubscriberInterface
{

    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public static function getSubscribedEvents()
    {
        // TODO: Implement getSubscribedEvents() method.
        return [
            KernelEvents::EXCEPTION => [
                ['handleEntityException', 1],
                ['handle404Exception', 0],
                ['handleGenericException', -1],
            ],
        ];
    }

    public function handle404Exception(ExceptionEvent $event)
    {
        if($event->getThrowable() instanceof NotFoundHttpException) {
            $response = HypermidiaResponse::fromError($event->getThrowable())->getResponse();
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
            $event->setResponse($response);
            /*
            //Caso queira redirecionar para outra rota caso a rota digitado pelo usuario não existe
            $event->setResponse(new RedirectResponse('http://localhost:8080/medicos'));
            */
        }
    }

    public function handleEntityException(ExceptionEvent $event)
    {
        if($event->getThrowable() instanceof EntityFactoryException) {
            $response = HypermidiaResponse::fromError($event->getThrowable())->getResponse();
            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
            $event->setResponse($response);
        }
    }

    public function handleGenericException(ExceptionEvent $event)
    {
        $this->logger->critical('Uma exceção ocrreu. {stack}', [
            'stack' => $event->getThrowable()->getTraceAsString()
        ]);
        $response = HypermidiaResponse::fromError($event->getThrowable())->getResponse();
        $event->setResponse($response);
    }
}
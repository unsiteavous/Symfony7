<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AccessDeniedListener implements EventSubscriberInterface
{
  public function __construct(private UrlGeneratorInterface $urlGenerator)
  {

  }
  public static function getSubscribedEvents(): array
  {
    return [
      // the priority must be greater than the Security HTTP
      // ExceptionListener, to make sure it's called before
      // the default exception listener
      KernelEvents::EXCEPTION => ['onKernelException', 2],
    ];
  }

  public function onKernelException(ExceptionEvent $event): void
  {
    $exception = $event->getThrowable();
    if (!$exception instanceof AccessDeniedException) {
      return;
    }

    // Récupérer le message d'erreur spécifié dans l'annotation #[IsGranted]
    $message = $exception->getMessage();

    // Générer une réponse avec le message d'erreur
    $event->getRequest()->getSession()->getFlashBag()->add('note', $message);
    $response = new RedirectResponse($this->urlGenerator->generate('app_film_index'));

    // Envoyer la réponse
    $event->setResponse($response);

    // puis stopper la propagation (éviter que d'autres écouteurs d'exceptions soient appelés)
    $event->stopPropagation();
  }
}

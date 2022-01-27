<?php

declare(strict_types=1);

namespace Th3Mouk\SimpleAPI\Symfony\EventListener;

use FOS\RestBundle\View\View;
use FOS\RestBundle\View\ViewHandlerInterface;
use Safe\Exceptions\PcreException;
use Safe\Exceptions\StringsException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\Exception\PartialDenormalizationException;
use Th3Mouk\SimpleAPI\Symfony\Validator\ConstraintViolationListFactory;

/**
 * The aims here is to recover from exception thrown by FosRestBundle when a DTO is denormalized with
 * DenormalizerInterface::COLLECT_DENORMALIZATION_ERRORS option, and types are mismatching with request.
 *
 * In the context where our DTO attribute target a non-nullable param in the controller action, we are forced to
 * manipulate the response directly in an exception listener to avoid a sub-request.
 * In order to not redesign and test anything, reuse the normal behavior of FosRestBundle seems the better
 * idea to generate the appropriate response.
 */

final class PartialDenormalizationListener implements EventSubscriberInterface
{
    public function __construct(private ViewHandlerInterface $viewHandler)
    {
    }

    /**
     * @throws PcreException
     * @throws StringsException
     */
    public function onKernelException(ExceptionEvent $event): void
    {
        $originalException = $event->getThrowable()->getPrevious();
        if (!$originalException || !$originalException instanceof PartialDenormalizationException) {
            return;
        }

        $constraintViolationList =
            ConstraintViolationListFactory::fromPartialDenormalizationException($originalException);

        $request = $event->getRequest();
        $view    = View::create($constraintViolationList);

        if ($view->getFormat() === null) {
            $view->setFormat($request->getRequestFormat('json'));
        }

        $response = $this->viewHandler->handle($view, $request);

        $event->setResponse($response);
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::EXCEPTION => ['onKernelException', -96]];
    }
}

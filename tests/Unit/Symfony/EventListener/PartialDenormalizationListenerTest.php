<?php

declare(strict_types=1);

use FOS\RestBundle\View\ViewHandlerInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Th3Mouk\SimpleAPI\Symfony\EventListener\PartialDenormalizationListener;

it('must return early without adding response when no exception is embed', function (): void {
    $viewHandler = $this->createMock(ViewHandlerInterface::class);
    $listener    = new PartialDenormalizationListener($viewHandler);

    $event = new ExceptionEvent(
        $this->createMock(HttpKernel::class),
        new Request(),
        HttpKernelInterface::MAIN_REQUEST,
        new BadRequestException('', 0),
    );

    $listener->onKernelException($event);

    expect($event->getResponse())->toBeNull();
});

it('must return early without adding response when embed exception is not of type
PartialDenormalizationException', function (): void {
    $viewHandler = $this->createMock(ViewHandlerInterface::class);
    $listener    = new PartialDenormalizationListener($viewHandler);

    $event = new ExceptionEvent(
        $this->createMock(HttpKernel::class),
        new Request(),
        HttpKernelInterface::MAIN_REQUEST,
        new BadRequestException('', 0, new LogicException()),
    );

    $listener->onKernelException($event);

    expect($event->getResponse())->toBeNull();
});

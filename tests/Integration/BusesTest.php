<?php

declare(strict_types=1);

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Tests\Integration\Symfony\Messenger\DummyCommand;
use Tests\Integration\Symfony\Messenger\DummyEvent;
use Tests\Integration\Symfony\Messenger\DummyQuery;
use Tests\Integration\Symfony\Messenger\SpyCommandHandler;
use Tests\Integration\Symfony\Messenger\SpyEventHandler;
use Tests\Integration\Symfony\Messenger\StubQueryHandler;

uses(KernelTestCase::class);

test('commands are dispatched', function (): void {
    $bus = $this->getContainer()->get('command.bus');
    assert($bus instanceof MessageBusInterface);

    $bus->dispatch(new DummyCommand());

    $handler = $this->getContainer()->get(SpyCommandHandler::class);
    assert($handler instanceof SpyCommandHandler);

    expect($handler->invoked)->toBeTrue();
});

test('events are dispatched', function (): void {
    $bus = $this->getContainer()->get('event.bus');
    assert($bus instanceof MessageBusInterface);

    $bus->dispatch(new DummyEvent());

    $handler = $this->getContainer()->get(SpyEventHandler::class);
    assert($handler instanceof SpyEventHandler);

    expect($handler->invoked)->toBeTrue();
});

test('queries are dispatched', function (): void {
    $bus = $this->getContainer()->get('query.bus');
    assert($bus instanceof MessageBusInterface);

    $envelop = $bus->dispatch(new DummyQuery());

    $handler = $this->getContainer()->get(StubQueryHandler::class);
    assert($handler instanceof StubQueryHandler);

    expect($envelop->last(HandledStamp::class)->getResult())->toBe(StubQueryHandler::RETURNED_VALUE);
});

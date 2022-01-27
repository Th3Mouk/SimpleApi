<?php

declare(strict_types=1);

namespace Tests\Integration\Symfony\Messenger;

use Th3Mouk\SimpleAPI\Symfony\Messenger\EventHandler;

final class SpyEventHandler implements EventHandler
{
    public bool $invoked = false;

    public function __invoke(DummyEvent $event): void
    {
        $this->invoked = true;
    }
}

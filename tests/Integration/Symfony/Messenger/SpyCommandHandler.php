<?php

declare(strict_types=1);

namespace Tests\Integration\Symfony\Messenger;

use Th3Mouk\SimpleAPI\Symfony\Messenger\CommandHandler;

final class SpyCommandHandler implements CommandHandler
{
    public bool $invoked = false;

    public function __invoke(DummyCommand $command): void
    {
        $this->invoked = true;
    }
}

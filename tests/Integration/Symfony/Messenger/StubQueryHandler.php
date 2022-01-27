<?php

declare(strict_types=1);

namespace Tests\Integration\Symfony\Messenger;

use Th3Mouk\SimpleAPI\Symfony\Messenger\QueryHandler;

final class StubQueryHandler implements QueryHandler
{
    public const RETURNED_VALUE = 'result';

    public function __invoke(DummyQuery $query): string
    {
        return self::RETURNED_VALUE;
    }
}

<?php

declare(strict_types=1);

namespace Tests\Functional\Rest;

final class SpyRestService
{
    /** @var object[] */
    private array $calls = [];

    public function spy(object $object): void
    {
        $this->calls[] = $object;
    }

    public function getCall(int $index = 0): ?object
    {
        return $this->calls[$index] ?? null;
    }

    public function reset(): void
    {
        $this->calls = [];
    }
}

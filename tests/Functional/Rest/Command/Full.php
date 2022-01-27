<?php

declare(strict_types=1);

namespace Tests\Functional\Rest\Command;

/**
 * @psalm-immutable
 */
final class Full
{
    /**
     * @param array<array-key, mixed> $array
     */
    public function __construct(
        public string $string,
        public bool $bool,
        public float $float,
        public int $int,
        public array $array,
        public string $camelCase,
        public ?string $nullable = null,
    ) {
    }
}

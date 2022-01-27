<?php

declare(strict_types=1);

namespace Th3Mouk\SimpleAPI\Common;

use Ramsey\Uuid\UuidInterface;

/**
 * @psalm-pure
 */
trait Identifier
{
    private function __construct(
        private string $uuid,
    ) {
    }

    public static function fromUuid(UuidInterface $uuid): static
    {
        return new static($uuid->toString());
    }

    public static function fromString(string $uuid): static
    {
        return new static($uuid);
    }

    public function __toString(): string
    {
        return $this->uuid;
    }
}

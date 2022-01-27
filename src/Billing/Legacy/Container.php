<?php

declare(strict_types=1);

namespace Th3Mouk\SimpleAPI\Billing\Legacy;

use Psr\Container\ContainerInterface;

final class Container implements ContainerInterface
{
    /** @var array<object> */
    private array $map = [];

    /**
     * @param array<object> $objects
     */
    private function __construct(array $objects = [])
    {
        $this->map = [];
        foreach ($objects as $object) {
            $this->map[$object::class] = $object;
        }
    }

    public static function create(object ...$objects): self
    {
        return new self($objects);
    }

    public function has(string $id): bool
    {
        return isset($this->map[$id]);
    }

    public function get(string $id): object
    {
        return $this->map[$id];
    }

    public function add(object $object): void
    {
        $this->map[$object::class] = $object;
    }
}

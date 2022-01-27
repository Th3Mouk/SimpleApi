<?php

declare(strict_types=1);

namespace Th3Mouk\SimpleAPI\Persistence;

/**
 * @template T
 */
interface Recordable
{
    /**
     * @psalm-param T $object
     *
     * @throws CannotPersistObject
     */
    public function persist($object): void;
}

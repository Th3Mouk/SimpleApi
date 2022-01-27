<?php

declare(strict_types=1);

namespace Th3Mouk\SimpleAPI\Persistence;

use Doctrine\ORM\Exception\ORMException;

final class CannotPersistObject extends \Exception
{
    public static function dueToDoctrine(ORMException $exception): self
    {
        return new self(
            $exception->getMessage(),
            (int) $exception->getCode(),
            $exception
        );
    }
}

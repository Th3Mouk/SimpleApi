<?php

declare(strict_types=1);

namespace Th3Mouk\SimpleAPI\Customer\View;

/**
 * @psalm-immutable
 */
final class SocietyCreatedWithAdministrator
{
    public function __construct(
        public string $societyUuid,
        public string $administratorUuid,
    ) {
    }
}

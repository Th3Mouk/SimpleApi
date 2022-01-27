<?php

declare(strict_types=1);

namespace Th3Mouk\SimpleAPI\Customer\View;

/**
 * @psalm-immutable
 */
final class CustomerProfile
{
    public function __construct(
        public string $firstName = 'Elliot',
        public string $lastName = 'Alderson',
    ) {
    }
}

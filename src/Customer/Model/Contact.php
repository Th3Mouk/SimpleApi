<?php

declare(strict_types=1);

namespace Th3Mouk\SimpleAPI\Customer\Model;

/**
 * @psalm-immutable
 */
final class Contact
{
    private function __construct(
        public string $gender,
        public string $lastName,
        public string $email,
        public ?string $firstName,
        public ?string $phoneNumber,
    ) {
    }

    public static function create(
        string $gender,
        string $lastName,
        string $email,
        ?string $firstName = null,
        ?string $phoneNumber = null,
    ): self {
        return new self(
            $gender,
            $lastName,
            $email,
            $firstName,
            $phoneNumber
        );
    }
}

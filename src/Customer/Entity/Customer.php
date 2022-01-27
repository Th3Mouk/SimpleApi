<?php

declare(strict_types=1);

namespace Th3Mouk\SimpleAPI\Customer\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Th3Mouk\SimpleAPI\Customer\Model\CustomerIdentifier;

#[Entity]
class Customer
{
    /**
     * @param array<string> $roles
     */
    private function __construct(
        #[Id,
        Column(type: Types::GUID),
        GeneratedValue(strategy: 'NONE')]
        private string $uuid,
        #[Column]
        private array $roles,
        #[Column(nullable: true)]
        private string $gender,
        #[Column(nullable: true)]
        private ?string $firstName,
        #[Column(nullable: true)]
        private string $lastName,
        #[Column(nullable: true)]
        private string $email,
        #[Column(nullable: true)]
        private ?string $phoneNumber
    ) {
    }

    public static function fromProspection(
        CustomerIdentifier $uuid,
        string $gender,
        string $lastName,
        string $email,
        ?string $firstName = null,
        ?string $phoneNumber = null,
    ): self {
        return new self((string) $uuid, ['ROLE_CONTACT'], $gender, $firstName, $lastName, $email, $phoneNumber);
    }
}

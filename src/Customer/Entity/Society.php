<?php

declare(strict_types=1);

namespace Th3Mouk\SimpleAPI\Customer\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Th3Mouk\SimpleAPI\Customer\Model\SocietyIdentifier;

#[Entity]
class Society
{
    private function __construct(
        #[Id,
        Column(type: Types::GUID),
        GeneratedValue(strategy: 'NONE')]
        private string $uuid,
        #[Column]
        private string $name,
    ) {
    }

    public static function create(
        SocietyIdentifier $uuid,
        string $name,
    ): self {
        return new self((string) $uuid, $name);
    }
}

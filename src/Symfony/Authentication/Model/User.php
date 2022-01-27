<?php

declare(strict_types=1);

namespace Th3Mouk\SimpleAPI\Symfony\Authentication\Model;

use Lexik\Bundle\JWTAuthenticationBundle\Security\User\JWTUserInterface;

final class User implements JWTUserInterface
{
    /**
     * @param array<string> $roles
     */
    public function __construct(private string $identifier, private array $roles = [])
    {
    }

    /**
     * {@inheritdoc}
     */
    public static function createFromPayload($username, array $payload): User
    {
        if (isset($payload['roles'])) {
            return new self($username, (array) $payload['roles']);
        }

        return new self($username);
    }

    public function getUsername(): string
    {
        return $this->identifier;
    }

    public function getUserIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    public function getPassword(): ?string
    {
        return null;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials(): void
    {
    }
}

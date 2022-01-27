<?php

declare(strict_types=1);

namespace Th3Mouk\SimpleAPI\Customer\Command;

use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Uuid;
use Th3Mouk\SimpleAPI\Symfony\Messenger\Command;

/**
 * @psalm-immutable
 */
final class AddSocietyWithContact implements Command
{
    public function __construct(
        #[Uuid]
        public string $societyUuid,
        #[NotBlank]
        public string $societyName,
        #[Uuid]
        public string $contactUuid,
        #[Email]
        public string $email,
        #[Choice(choices: ['male', 'female', 'other'])]
        public string $gender,
        #[NotBlank]
        public string $lastName,
        #[NotBlank(allowNull: true)]
        public ?string $firstName,
        public ?string $phoneNumber
    ) {
    }

    public static function fromAddSocietyWithContactDTO(
        string $societyUuid,
        string $contactUuid,
        \Th3Mouk\SimpleAPI\Customer\Controller\Dto\AddSocietyWithContact $dto
    ): self {
        return new self(
            $societyUuid,
            $dto->societyName,
            $contactUuid,
            $dto->email,
            $dto->gender,
            $dto->lastName,
            $dto->firstName,
            $dto->phoneNumber,
        );
    }
}

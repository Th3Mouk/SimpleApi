<?php

declare(strict_types=1);

namespace Th3Mouk\SimpleAPI\Customer\Controller\Dto;

use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @psalm-immutable
 */
final class AddSocietyWithContact
{
    public function __construct(
        #[NotBlank]
        public string $societyName,
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
}

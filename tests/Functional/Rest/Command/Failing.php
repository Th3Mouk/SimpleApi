<?php

declare(strict_types=1);

namespace Tests\Functional\Rest\Command;

use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\PositiveOrZero;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @psalm-immutable
 */
final class Failing
{
    /**
     * @param array<string> $strings
     */
    private function __construct(
        public string $yetCorrect,
        #[IsTrue]
        public bool $bool,
        #[Email]
        public string $email,
        #[NotBlank]
        public string $notNull,
        #[PositiveOrZero]
        public int $positive,
        #[Choice(['valid-choice'])]
        public string $choice,
        /** @All({@Length(min=5)}) */
        public array $strings,
    ) {
    }

    #[Callback]
    public function validate(ExecutionContextInterface $context): void
    {
        $context->buildViolation('Oh ! Something goods this time :)')
            ->atPath('yetCorrect')
            ->addViolation();
    }
}

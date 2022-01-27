<?php

declare(strict_types=1);

namespace Th3Mouk\SimpleAPI\Symfony\Validator;

use Safe\Exceptions\PcreException;
use Safe\Exceptions\StringsException;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Exception\PartialDenormalizationException;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

use function Safe\preg_match;
use function Safe\sprintf;

final class ConstraintViolationListFactory
{
    /**
     * @throws PcreException
     * @throws StringsException
     */
    public static function fromPartialDenormalizationException(
        PartialDenormalizationException $exception
    ): ConstraintViolationList {
        $constraintViolationList = new ConstraintViolationList();

        foreach ($exception->getErrors() as $exception) {
            /** @var NotNormalizableValueException $exception */
            $message    = sprintf(
                'The type must be one of "%s" ("%s" given).',
                implode(', ', $exception->getExpectedTypes() ?? []),
                $exception->getCurrentType(),
            );
            $parameters = [];
            if ($exception->canUseMessageForUser()) {
                $parameters['hint'] = $exception->getMessage();
            }

            $matchs = [];
            if (preg_match('/object miss the \"(\w+)\" property\./', $exception->getMessage(), $matchs) !== 0) {
                $message = 'This parameter is mandatory';
                unset($parameters['hint']);
            }

            $property = $exception->getPath() ?? $matchs[1] ?? null;

            $constraintViolationList->add(
                new ConstraintViolation(
                    $message,
                    '',
                    $parameters,
                    null,
                    $property,
                    null
                )
            );
        }

        return $constraintViolationList;
    }
}

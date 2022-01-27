<?php

declare(strict_types=1);

use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Th3Mouk\SimpleAPI\Symfony\Attribute\DTO;

it('must merge correctly options activating denormalization error collection', function (): void {
    $attribute = new DTO('class', 'parameter', options: ['test-option' => 'activated']);
    expect($attribute->getOptions())->toBe([
        'test-option'            => 'activated',
        'deserializationContext' => [DenormalizerInterface::COLLECT_DENORMALIZATION_ERRORS => true],
    ]);
});

test('parameter is not optional', function (): void {
    $attribute = new DTO('class', 'parameter', options: ['test-option' => 'activated']);
    expect($attribute->isOptional())->toBeFalsy();
});

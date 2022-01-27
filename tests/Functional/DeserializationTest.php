<?php

declare(strict_types=1);

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tests\Functional\Rest\Command\Full;
use Tests\Functional\Rest\SpyRestService;
use Th3Mouk\SimpleAPI\Symfony\Authentication\Model\User;

use function Safe\json_decode;

uses(WebTestCase::class);

test('framework deserialize/denormalize DTO correctly when attribute is defined on controller', function (): void {
    $client = $this->createClient()->loginUser(new User('test'), 'api');
    $client->jsonRequest(
        'POST',
        '/test/command-deserialization-without-validation-attributes',
        [
            'string'           => 'string',
            'bool'             => true,
            'float'            => 1.2,
            'int'              => 1,
            'array'            => ['string'],
            'camel_case'       => 'FTW',
            'unexpected_value' => '',
        ],
    );

    $this->assertResponseIsSuccessful();
    $spyService = $this->getContainer()->get(SpyRestService::class);
    assert($spyService instanceof SpyRestService);

    $dto = $spyService->getCall();
    assert($dto instanceof Full);
    expect($dto)->toBeInstanceOf(Full::class);
    expect($dto->string)->toBe('string');
    expect($dto->bool)->toBe(true);
    expect($dto->float)->toBe(1.2);
    expect($dto->int)->toBe(1);
    expect($dto->array)->toBe(['string']);
    expect($dto->nullable)->toBe(null);
});

/**
 * This test is acting for PartialDenormalizationListener and ConstraintViolationListFactory unit tests too
 */
test('framework raise an understanding error when a property is missing or contains a typo', function (): void {
    $client = $this->createClient()->loginUser(new User('test'), 'api');
    $client->jsonRequest(
        'POST',
        '/test/command-deserialization-without-validation-attributes',
        [
            'string'    => 'string',
            'bool'      => true,
            'float'     => 1.2,
            'int'       => 1,
            'array'     => ['string'],
            'camelCase' => 'FTW',
        ],
    );

    $this->assertResponseStatusCodeSame(400);
    $content = json_decode($client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);

    expect($content['detail'])->not->toBeEmpty();
    unset($content['detail']);

    expect($content)->toBe([
        'type'       => 'https://symfony.com/errors/validation',
        'title'      => 'Validation Failed',
        'violations' => [
            [
                'propertyPath' => 'camel_case',
                'title'        => 'This parameter is mandatory',
                'parameters'   => [],
            ],
        ],
    ]);
});

/**
 * This test is acting for PartialDenormalizationListener and ConstraintViolationListFactory unit tests too
 */
test('type errors are collected first on DTO properties', function (): void {
    $client = $this->createClient()->loginUser(new User('test'), 'api');
    $client->jsonRequest(
        'POST',
        '/test/command-deserialization-with-validation-attributes',
        [
            'yet_correct' => '',
            'bool'        => 'notBool',
            'email'       => 'notEmail',
            'not_null'    => null,
            'positive'    => -1,
            'choice'      => 'inexistent-choice',
            'strings'     => ['x'],
        ],
    );

    $this->assertResponseStatusCodeSame(400);
    $content = json_decode($client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);

    expect($content['detail'])->not->toBeEmpty();
    unset($content['detail']);

    expect($content)->toBe([
        'type'       => 'https://symfony.com/errors/validation',
        'title'      => 'Validation Failed',
        'violations' => [
            [
                'propertyPath' => 'bool',
                'title'        => 'The type must be one of "bool" ("string" given).',
                'parameters'   => [],
            ],
            [
                'propertyPath' => 'not_null',
                'title'        => 'The type must be one of "string" ("null" given).',
                'parameters'   => [],
            ],
        ],
    ]);
});

test('validation on DTO properties and class', function (): void {
    $client = $this->createClient()->loginUser(new User('test'), 'api');
    $client->jsonRequest(
        'POST',
        '/test/command-deserialization-with-validation-attributes',
        [
            'yet_correct' => '',
            'bool'        => false,
            'email'       => 'notEmail',
            'not_null'    => '',
            'positive'    => -1,
            'choice'      => 'inexistent-choice',
            'strings'     => ['x'],
        ],
    );

    $this->assertResponseStatusCodeSame(400);
    $content = json_decode($client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);

    expect($content['detail'])->not->toBeEmpty();
    unset($content['detail']);

    expect($content)->toBe([
        'type'       => 'https://symfony.com/errors/validation',
        'title'      => 'Validation Failed',
        'violations' => [
            [
                'propertyPath' => 'yet_correct',
                'title'        => 'Oh ! Something goods this time :)',
                'parameters'   => [],
            ],
            [
                'propertyPath' => 'bool',
                'title'        => 'This value should be true.',
                'parameters'   => ['{{ value }}' => 'false'],
                'type'         => 'urn:uuid:2beabf1c-54c0-4882-a928-05249b26e23b',
            ],
            [
                'propertyPath' => 'email',
                'title'        => 'This value is not a valid email address.',
                'parameters'   => ['{{ value }}' => '"notEmail"'],
                'type'         => 'urn:uuid:bd79c0ab-ddba-46cc-a703-a7a4b08de310',
            ],
            [
                'propertyPath' => 'not_null',
                'title'        => 'This value should not be blank.',
                'parameters'   => ['{{ value }}' => '""'],
                'type'         => 'urn:uuid:c1051bb4-d103-4f74-8988-acbcafc7fdc3',
            ],
            [
                'propertyPath' => 'positive',
                'title'        => 'This value should be either positive or zero.',
                'parameters'   => [
                    '{{ value }}'               => '-1',
                    '{{ compared_value }}'      => '0',
                    '{{ compared_value_type }}' => 'int',
                ],
                'type'         => 'urn:uuid:ea4e51d1-3342-48bd-87f1-9e672cd90cad',
            ],
            [
                'propertyPath' => 'choice',
                'title'        => 'The value you selected is not a valid choice.',
                'parameters'   => [
                    '{{ value }}'   => '"inexistent-choice"',
                    '{{ choices }}' => '"valid-choice"',
                ],
                'type'         => 'urn:uuid:8e179f1b-97aa-4560-a02f-2a8b42e49df7',
            ],
            [
                'propertyPath' => 'strings[0]',
                'title'        => 'This value is too short. It should have 5 characters or more.',
                'parameters'   => [
                    '{{ value }}' => '"x"',
                    '{{ limit }}' => '5',
                ],
                'type'         => 'urn:uuid:9ff3fdc4-b214-49db-8718-39c315e33d45',
            ],
        ],
    ]);
});

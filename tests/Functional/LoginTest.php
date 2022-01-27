<?php

declare(strict_types=1);

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use function Safe\json_decode;

uses(WebTestCase::class);

test('can authenticate and receive a JWT token from in_memory provider', function (): void {
    $client = $this->createClient();
    $client->jsonRequest(
        'POST',
        '/login',
        ['identifier' => 'test', 'password' => 'test'],
    );

    $content = json_decode($client->getResponse()->getContent());

    $this->assertResponseIsSuccessful();
    expect($content->token)->toBeString();
});

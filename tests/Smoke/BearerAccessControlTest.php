<?php

declare(strict_types=1);

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

uses(WebTestCase::class);

test('/profile must be secured behind JWT authenticator and return a 401 without Bearer header', function (): void {
    $client = $this->createClient();
    $client->jsonRequest(
        'GET',
        '/profile',
    );

    expect($client->getResponse())
        ->getStatusCode()->toBe(401)
        ->getMessage()->toBe('JWT Token not found');
});

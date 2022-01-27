<?php

declare(strict_types=1);

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Th3Mouk\SimpleAPI\Symfony\Authentication\Model\User;

use function Safe\json_decode;

uses(WebTestCase::class);

test('/profile returns the correct information', function (): void {
    $client = $this->createClient()->loginUser(new User('test'), 'api');
    $client->jsonRequest(
        'GET',
        '/profile',
    );

    $this->assertResponseIsSuccessful();
    $content = json_decode($client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);
    expect($content)->toBe([
        'first_name' => 'Elliot',
        'last_name'  => 'Alderson',
    ]);
});

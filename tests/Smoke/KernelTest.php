<?php

declare(strict_types=1);

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpKernel\KernelInterface;

uses(KernelTestCase::class);

it('must boot without exception in production env', function (): void {
    $kernel = $this->bootKernel([
        'environment' => 'prod',
        'debug'       => false,
    ]);

    expect($kernel)->toBeInstanceOf(KernelInterface::class);
});

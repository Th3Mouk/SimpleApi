<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Th3Mouk\SimpleAPI\Billing\Legacy\CartService;
use Th3Mouk\SimpleAPI\Billing\Legacy\TaxProvider;

test('cart calculation is correct with percentage discount', function (): void {
    $container   = $this->createMock(ContainerInterface::class);
    $taxProvider = $this->createMock(TaxProvider::class);

    $container->method('get')->with(TaxProvider::class)->willReturn($taxProvider);
    $taxProvider->method('getTax')->willReturn(20);

    $service = new CartService($container);

    $total = $service->calculate([['price' => 10]], 'percentage', 10);

    expect($total)->toBe([
        'ht'  => 9,
        'tva' => 1.8,
        'ttc' => 10.8,
    ]);
});

test('cart calculation is correct with amount discount', function (): void {
    $container   = $this->createMock(ContainerInterface::class);
    $taxProvider = $this->createMock(TaxProvider::class);

    $container->method('get')->with(TaxProvider::class)->willReturn($taxProvider);
    $taxProvider->method('getTax')->willReturn(20);

    $service = new CartService($container);

    $total = $service->calculate([['price' => 10]], 'amount', 5);

    expect($total)->toBe([
        'ht'  => 5,
        'tva' => 1,
        'ttc' => 6,
    ]);
});

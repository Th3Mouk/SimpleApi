<?php

declare(strict_types=1);

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Routing\Route;

uses(KernelTestCase::class);

test('endpoints are available', function (): void {
    $container = $this->getContainer();

    $router = $container->get('router');
    assert($router instanceof Router);

    $routeCollection = $router->getRouteCollection();

    $expectedRoutes = [
        'customer_get_profile' => [
            'path'    => '/profile',
            'methods' => ['GET', 'HEAD'],
        ],
        'society_create'       => [
            'path'    => '/society/create',
            'methods' => ['POST'],
        ],
        'login'                => [
            'path'    => '/login',
            'methods' => ['POST'],
        ],
    ];

    $serializer = static fn (Route $route): array => ['path' => $route->getPath(), 'methods' => $route->getMethods()];
    $filter     = static fn (string $routeName): bool => !str_starts_with($routeName, 'tests_');
    expect($expectedRoutes)->toBe(
        array_map($serializer, array_filter($routeCollection->all(), $filter, ARRAY_FILTER_USE_KEY))
    );
});

<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services()
        ->defaults()
        ->autoconfigure()
        ->autowire();

    $namespaces = [
        'Customer',
        'Notification',
        'Persistence',
        'Symfony',
    ];

    $excludes = [
        'Collection',
        'Command',
        'DependencyInjection',
        'Entity',
        'Exception',
        'Event',
        'Model',
        'Kernel.php',
    ];

    $excludes = '{' . implode(',', $excludes) . '}';

    foreach ($namespaces as $namespace) {
        $services
            ->load("Th3Mouk\\SimpleAPI\\{$namespace}\\", "../src/{$namespace}/*")
            ->exclude("../src/{$namespace}/**/{$excludes}");
    }
};

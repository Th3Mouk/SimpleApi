<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services()
        ->defaults()
        ->autoconfigure()
        ->autowire();

    $namespaces = ['Symfony'];

    foreach ($namespaces as $namespace) {
        $services->load("Tests\\Integration\\{$namespace}\\", "../../../tests/Integration/{$namespace}/*");
    }
};

<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services()
        ->defaults()
        ->autoconfigure()
        ->autowire();

    $services
        ->load('Tests\\Functional\\Rest\\', '../../../tests/Functional/Rest/*')
        ->exclude('../../../tests/Functional/Rest/Command');
};

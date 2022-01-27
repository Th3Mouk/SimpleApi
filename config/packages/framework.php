<?php

declare(strict_types=1);

// see https://symfony.com/doc/current/reference/configuration/framework.html

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Symfony\Config\FrameworkConfig;

return static function (FrameworkConfig $config, ContainerConfigurator $container): void {
    $config->secret('%env(APP_SECRET)%');

    $config->csrfProtection()->enabled(false);

    $config->httpMethodOverride(false);

    $config->phpErrors()->log(true);

    $config->router()->utf8(true);

    $config->session()->enabled(false);

    $config->serializer()->nameConverter('serializer.name_converter.camel_case_to_snake_case');

    if ($container->env() === 'prod') {
        $config->router()->strictRequirements(null);
    }

    if ($container->env() !== 'test') {
        return;
    }

    $config->test(true);
};

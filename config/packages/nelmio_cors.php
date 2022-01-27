<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Symfony\Config\NelmioCorsConfig;

return static function (NelmioCorsConfig $config): void {
    $config->defaults()
        ->originRegex(true)
        ->allowOrigin(['%env(CORS_ALLOW_ORIGIN)%'])
        ->allowMethods(['GET', 'OPTIONS', 'POST', 'PUT', 'PATCH', 'DELETE'])
        ->allowHeaders(['Content-Type', 'Authorization'])
        ->exposeHeaders(['Link'])
        ->maxAge(3600);

    $config->paths('^/', []);
};

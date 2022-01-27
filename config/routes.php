<?php

declare(strict_types=1);

namespace Symfony\Component\Routing\Loader\Configurator;

return static function (RoutingConfigurator $routes): void {
    $routes->import('../src/**/Controller/*', 'annotation')
        ->requirements(['_format' => 'json']);

    $routes->add('login', '/login')
        ->methods(['POST'])
        ->requirements(['_format' => 'json']);

//    $routes->add('openapi', '/{area}.json')
//        ->methods(['GET'])
//        ->defaults([
//            '_controller' => 'nelmio_api_doc.controller.swagger',
//            'area' => 'default'
//        ]);

    if ($routes->env() === 'test') {
        $routes->import('../tests/**/Controller/*', 'annotation')
            ->requirements(['_format' => 'json']);
    }

    if ($routes->env() !== 'dev') {
        return;
    }

    $routes->import('@FrameworkBundle/Resources/config/routing/errors.xml')
        ->prefix('/_error');
};

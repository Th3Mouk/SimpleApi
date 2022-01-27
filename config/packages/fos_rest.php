<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Symfony\Config\FosRestConfig;

// Read the documentation: https://symfony.com/doc/master/bundles/FOSRestBundle/index.html
return static function (FosRestConfig $config): void {
    $view = $config->view();

    $view->viewResponseListener()->enabled(true);
    $view->format('json', true);
    $view->format('xml', true);

    $config->bodyConverter()
        ->enabled(true)
        ->validate(true)
        ->validationErrorsArgument('validationErrors');

    $config->bodyListener()
        ->arrayNormalizer()
        ->service('fos_rest.normalizer.camel_keys');

    $config->formatListener()
        ->enabled(true)
        ->rule()
        ->path('^/')->priorities(['json'])->fallbackFormat('json')->preferExtension(false);

    $config->exception()->flattenExceptionFormat('rfc7807');

    $config->allowedMethodsListener()->enabled(true);
};

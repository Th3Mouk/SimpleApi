<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Symfony\Config\FrameworkConfig;

return static function (FrameworkConfig $config): void {
    $config->messenger()->defaultBus('command.bus');

    $commandBus = $config->messenger()->bus('command.bus');
//    $commandBus->middleware()->id('validation');
    $commandBus->middleware()->id('doctrine_transaction');

    $queryBus = $config->messenger()->bus('query.bus');
//    $queryBus->middleware()->id('validation');

    $eventBus = $config->messenger()->bus('event.bus');
    // the 'allow_no_handlers' middleware allows to have no handler
    // configured for this bus without throwing an exception
    $eventBus->defaultMiddleware('allow_no_handlers');
//    $eventBus->middleware()->id('validation');
};

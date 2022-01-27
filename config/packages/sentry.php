<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Symfony\Config\SentryConfig;

return static function (SentryConfig $config): void {
    $config->dsn('%env(SENTRY_DSN)%');

    $config->registerErrorListener(false);

    $config->options(['send_default_pii' => true]);
};

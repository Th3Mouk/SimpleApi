<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Symfony\Config\SensioFrameworkExtraConfig;

return static function (SensioFrameworkExtraConfig $config): void {
    $config->cache()->annotations(false);
    $config->request()->autoConvert(true);
    $config->router()->annotations(false);
    $config->security()->annotations(false);
    $config->view()->annotations(true);
};

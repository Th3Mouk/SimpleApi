<?php

declare(strict_types=1);

use Sentry\State\HubInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension('monolog', [
        'handlers' => [
            'main' => [
                'type' => 'group',
                'members' => ['stderr', 'stdout'],
                'buffer_size' => 50,
            ],
            'stderr' => [
                'type' => 'stream',
                'path' => 'php://stderr',
                'level' => 'info',
            ],
            'stdout' => [
                'type' => 'stream',
                'path' => 'php://stdout',
                'level' => 'info',
            ],
            'sentry' => [
                'type' => 'sentry',
                'level' => 400,
                'hub_id' => HubInterface::class,
            ],
        ],
    ]);
};

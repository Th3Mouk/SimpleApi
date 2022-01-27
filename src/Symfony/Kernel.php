<?php

declare(strict_types=1);

namespace Th3Mouk\SimpleAPI\Symfony;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

final class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    protected function configureContainer(ContainerConfigurator $container): void
    {
        $container->import('../../config/{packages}/*.php');
        $container->import('../../config/{packages}/' . $this->environment . '/*.php');
        $container->import('../../config/{services}.php');
        $container->import('../../config/{services}/*.php');
        $container->import('../../config/{services}/' . $this->environment . '/*.php');
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        if (!is_file($path = \dirname(__DIR__) . '/../config/routes.php')) {
            return;
        }

        (require $path)($routes->withPath($path), $this);
    }
}

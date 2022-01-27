<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Symfony\Component\Security\Core\Authorization\Voter\AuthenticatedVoter;
use Symfony\Component\Security\Core\User\InMemoryUser;
use Symfony\Config\SecurityConfig;
use Th3Mouk\SimpleAPI\Symfony\Authentication\Model\User;

return static function (SecurityConfig $config, ContainerConfigurator $container): void {
    $config->enableAuthenticatorManager(true);

//    $loginProvider = 'database';

    $config->passwordHasher(User::class)->algorithm('plaintext');
    $config->passwordHasher(InMemoryUser::class)->algorithm('plaintext');

//    if ($container->env() === 'test') {
        $memoryProvider = $config->provider('in_memory')->memory();
        $memoryProvider->user('test')->password('test');
        $loginProvider = 'in_memory';
//    }

    $config->provider('jwt')->lexikJwt()->class(User::class);

    $config->firewall('login')
        ->pattern('/login')
        ->stateless(true)
        ->provider($loginProvider)
        ->jsonLogin()
            ->checkPath('/login')
            ->usernamePath('identifier')
            ->passwordPath('password')
            ->successHandler('lexik_jwt_authentication.handler.authentication_success')
            ->failureHandler('lexik_jwt_authentication.handler.authentication_failure');

    // https://symfony.com/doc/current/security/json_login_setup.html
    $config->firewall('api')
        ->stateless(true)
        ->provider('jwt')
        ->jwt();

    $config->accessControl()
        ->path('/login')
        ->roles([AuthenticatedVoter::PUBLIC_ACCESS]);

    $config->accessControl()
        ->path('/')
        ->roles([AuthenticatedVoter::IS_AUTHENTICATED_FULLY]);
};

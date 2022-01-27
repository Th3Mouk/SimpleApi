<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\ClassConst\VarConstantCommentRector;
use Rector\CodingStyle\Rector\ClassMethod\UnSpreadOperatorRector;
use Rector\CodingStyle\Rector\Encapsed\EncapsedStringsToSprintfRector;
use Rector\Core\Configuration\Option;
use Rector\DeadCode\Rector\Cast\RecastingRemovalRector;
use Rector\DeadCode\Rector\ClassMethod\RemoveUnusedPromotedPropertyRector;
use Rector\Doctrine\Set\DoctrineSetList;
use Rector\Privatization\Rector\Class_\FinalizeClassesWithoutChildrenRector;
use Rector\Set\ValueObject\SetList;
use Rector\Symfony\Set\SymfonySetList;
use Rector\Transform\Rector\Attribute\AttributeKeyToClassConstFetchRector;
use Rector\Transform\ValueObject\AttributeKeyToClassConstFetch;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    // get parameters
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::PATHS, [
        __DIR__ . '/config',
        __DIR__ . '/src',
        __DIR__ . '/tests',
        __DIR__ . '/rector.php',
    ]);

    // Define what rule sets will be applied
    $containerConfigurator->import(SetList::CODE_QUALITY);
    $containerConfigurator->import(SetList::CODING_STYLE);
    $containerConfigurator->import(SetList::DEAD_CODE);
    $containerConfigurator->import(DoctrineSetList::DOCTRINE_CODE_QUALITY);
    $containerConfigurator->import(SetList::PHP_56);
    $containerConfigurator->import(SetList::PHP_70);
    $containerConfigurator->import(SetList::PHP_71);
    $containerConfigurator->import(SetList::PHP_72);
    $containerConfigurator->import(SetList::PHP_73);
    $containerConfigurator->import(SetList::PHP_74);
    $containerConfigurator->import(SetList::PHP_80);
    $containerConfigurator->import(SetList::PRIVATIZATION);
    $containerConfigurator->import(SetList::SAFE_07);
    $containerConfigurator->import(SetList::TYPE_DECLARATION_STRICT);
    $containerConfigurator->import(SymfonySetList::SYMFONY_60);


    $parameters->set(Option::SKIP, [
        VarConstantCommentRector::class,
        EncapsedStringsToSprintfRector::class => [
            __DIR__ . '/config',
        ],
        __DIR__ . '/src/Symfony/Attribute/DTO.php',
        RecastingRemovalRector::class => [
            __DIR__ . '/src/Persistence/CannotPersistObject.php',
            __DIR__ . '/src/Persistence/Doctrine/ServiceEntityRepository.php',
        ],
        RemoveUnusedPromotedPropertyRector::class => [
            __DIR__ . '/src/Customer/Entity/Customer.php',
            __DIR__ . '/src/Customer/Entity/Society.php',
        ],
        UnSpreadOperatorRector::class,
        AttributeKeyToClassConstFetchRector::class,
        FinalizeClassesWithoutChildrenRector::class,
    ]);

    // get services (needed for register a single rule)
    // $services = $containerConfigurator->services();

    // register a single rule
    // $services->set(TypedPropertyRector::class);
};

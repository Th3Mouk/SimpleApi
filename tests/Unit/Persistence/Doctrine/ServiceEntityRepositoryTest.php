<?php

declare(strict_types=1);

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\InvalidEntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\ORMInvalidArgumentException;
use Doctrine\Persistence\ManagerRegistry;
use Th3Mouk\SimpleAPI\Persistence\CannotPersistObject;
use Th3Mouk\SimpleAPI\Persistence\Doctrine\ServiceEntityRepository;

test('ORMInvalidArgumentException is correctly caught', function (): void {
    $this->expectException(InvalidArgumentException::class);
    $this->expectExceptionMessage('EntityManager#persist() expects parameter 1 to be an entity object, string given.');

    $classMetadata = $this->createMock(ClassMetadata::class);
    $entityManager = $this->createMock(EntityManagerInterface::class);
    $entityManager->expects($this->once())
        ->method('getClassMetadata')
        ->with($this->equalTo('foo'))
        ->willReturn($classMetadata);
    $entityManager->expects($this->once())
        ->method('persist')
        ->with($this->equalTo('foo'))
        ->willThrowException(ORMInvalidArgumentException::invalidObject('EntityManager#persist()', 'foo'));

    $managerRegistry = $this->createMock(ManagerRegistry::class);
    $managerRegistry->expects($this->once())
        ->method('getManagerForClass')
        ->with($this->equalTo('foo'))
        ->willReturn($entityManager);

    $repository = new class ($managerRegistry, 'foo') extends ServiceEntityRepository {
    };

    $repository->persist('foo');
});

test('ORMException is correctly caught', function (): void {
    $this->expectException(CannotPersistObject::class);
    $this->expectExceptionMessage(
        'Invalid repository class \'foo\'. It must be a Doctrine\Persistence\ObjectRepository.'
    );

    $classMetadata = $this->createMock(ClassMetadata::class);
    $entityManager = $this->createMock(EntityManagerInterface::class);
    $entityManager->expects($this->once())
        ->method('getClassMetadata')
        ->with($this->equalTo('foo'))
        ->willReturn($classMetadata);
    $entityManager->expects($this->once())
        ->method('persist')
        ->with($this->equalTo('foo'))
        ->willThrowException(InvalidEntityRepository::fromClassName('foo'));

    $managerRegistry = $this->createMock(ManagerRegistry::class);
    $managerRegistry->expects($this->once())
        ->method('getManagerForClass')
        ->with($this->equalTo('foo'))
        ->willReturn($entityManager);

    $repository = new class ($managerRegistry, 'foo') extends ServiceEntityRepository {
    };

    $repository->persist('foo');
});

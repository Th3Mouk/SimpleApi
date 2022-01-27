<?php

declare(strict_types=1);

namespace Th3Mouk\SimpleAPI\Persistence\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository as Base;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\ORMInvalidArgumentException;
use InvalidArgumentException;
use Th3Mouk\SimpleAPI\Persistence\CannotPersistObject;
use Th3Mouk\SimpleAPI\Persistence\Recordable;

/**
 * @template T
 * @template-extends Base<T>
 * @template-implements Recordable<T>
 */
abstract class ServiceEntityRepository extends Base implements Recordable
{
    /**
     * @inheritDoc
     */
    public function persist($object): void
    {
        try {
            $this->_em->persist($object);
        } catch (ORMInvalidArgumentException $ormInvalidArgumentException) {
            throw new InvalidArgumentException(
                $ormInvalidArgumentException->getMessage(),
                (int) $ormInvalidArgumentException->getCode(),
                $ormInvalidArgumentException,
            );
        } catch (ORMException $ormException) {
            throw CannotPersistObject::dueToDoctrine($ormException);
        }
    }
}

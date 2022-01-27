<?php

declare(strict_types=1);

namespace Th3Mouk\SimpleAPI\Persistence\Doctrine;

use Doctrine\Persistence\ManagerRegistry;
use Th3Mouk\SimpleAPI\Customer\Entity\Society as SocietyEntity;
use Th3Mouk\SimpleAPI\Customer\Society;

/**
 * @extends ServiceEntityRepository<SocietyEntity>
 */
final class SocietyRepository extends ServiceEntityRepository implements Society
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SocietyEntity::class);
    }
}

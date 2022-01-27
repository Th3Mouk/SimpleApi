<?php

declare(strict_types=1);

namespace Th3Mouk\SimpleAPI\Persistence\Doctrine;

use Doctrine\Persistence\ManagerRegistry;
use Th3Mouk\SimpleAPI\Customer\Customer;
use Th3Mouk\SimpleAPI\Customer\Entity\Customer as CustomerEntity;

/**
 * @extends ServiceEntityRepository<CustomerEntity>
 */
final class CustomerRepository extends ServiceEntityRepository implements Customer
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CustomerEntity::class);
    }
}

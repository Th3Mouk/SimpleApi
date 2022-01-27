<?php

declare(strict_types=1);

namespace Th3Mouk\SimpleAPI\Customer;

use Th3Mouk\SimpleAPI\Customer\Entity\Customer as CustomerEntity;
use Th3Mouk\SimpleAPI\Persistence\Recordable;

/**
 * @extends Recordable<CustomerEntity>
 */
interface Customer extends Recordable
{
}

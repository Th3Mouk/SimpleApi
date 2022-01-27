<?php

declare(strict_types=1);

namespace Th3Mouk\SimpleAPI\Customer;

use Th3Mouk\SimpleAPI\Customer\Entity\Society as SocietyEntity;
use Th3Mouk\SimpleAPI\Persistence\Recordable;

/**
 * @extends Recordable<SocietyEntity>
 */
interface Society extends Recordable
{
}

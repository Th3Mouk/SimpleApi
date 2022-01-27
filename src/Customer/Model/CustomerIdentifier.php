<?php

declare(strict_types=1);

namespace Th3Mouk\SimpleAPI\Customer\Model;

use Th3Mouk\SimpleAPI\Common\Identifier;

/**
 * @psalm-pure
 */
final class CustomerIdentifier implements \Stringable
{
    use Identifier;
}

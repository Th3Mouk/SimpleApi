<?php

declare(strict_types=1);

namespace Th3Mouk\SimpleAPI\Symfony\Messenger;

use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

#[Autoconfigure(tags: [['name' => 'messenger.message_handler', 'bus' => 'query.bus']])]
interface QueryHandler
{
}

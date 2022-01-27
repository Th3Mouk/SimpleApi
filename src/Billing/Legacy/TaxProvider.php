<?php

declare(strict_types=1);

namespace Th3Mouk\SimpleAPI\Billing\Legacy;

use function Safe\sprintf;

class TaxProvider
{
    public function __construct(private HttpClient $httpClient)
    {
    }

    public function getTax(string $country): int
    {
        if (empty($country)) {
            throw new \InvalidArgumentException();
        }

        return (int) $this->httpClient->get(sprintf('tax.com/%s', $country));
    }
}

<?php

declare(strict_types=1);

namespace Th3Mouk\SimpleAPI\Billing\Legacy;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

final class CartService
{
    private ContainerInterface $container;

    public function __construct(?ContainerInterface $container = null)
    {
        $this->container = $container ?? Container::create(new TaxProvider(new HttpClient()));
    }

    /**
     * @param list<array{price: int}> $lines
     *
     * @return array{
     *  ht: float,
     *  tva: float,
     *  ttc: float}
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function calculate(array $lines, string $voucher, int $discount): array
    {
        $sum = 0;

        if ($voucher === 'percentage') {
            $sum = array_sum(array_column($lines, 'price')) * (100 - $discount) / 100;
        } elseif ($voucher === 'amount') {
            $sum = array_sum(array_column($lines, 'price')) - $discount;
        }

        $tva = $this->container->get(TaxProvider::class)->getTax('fr-fr');

        return [
            'ht'  => $sum,
            'tva' => $sum * $tva / 100,
            'ttc' => $sum + ($sum * $tva / 100),
        ];
    }
}

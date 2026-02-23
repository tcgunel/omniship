<?php

declare(strict_types=1);

namespace Omniship\Common;

readonly class Rate
{
    public function __construct(
        public string $carrier,
        public string $serviceCode,
        public string $serviceName,
        public float $totalPrice,
        public string $currency,
        public ?int $transitDays = null,
        public ?\DateTimeInterface $estimatedDelivery = null,
    ) {}
}

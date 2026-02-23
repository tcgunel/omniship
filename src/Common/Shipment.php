<?php

declare(strict_types=1);

namespace Omniship\Common;

use Omniship\Common\Enum\PaymentType;

class Shipment
{
    /**
     * @param Package[] $packages
     */
    public function __construct(
        public readonly Address $shipFrom,
        public readonly Address $shipTo,
        public readonly array $packages,
        public readonly ?string $serviceCode = null,
        public readonly ?string $reference = null,
        public readonly ?string $description = null,
        public readonly ?string $invoiceNumber = null,
        public readonly PaymentType $paymentType = PaymentType::SENDER,
        public readonly bool $cashOnDelivery = false,
        public readonly ?float $codAmount = null,
        public readonly ?string $codCurrency = null,
        public readonly ?\DateTimeInterface $shipDate = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'shipFrom' => $this->shipFrom->toArray(),
            'shipTo' => $this->shipTo->toArray(),
            'packages' => array_map(fn (Package $p) => $p->toArray(), $this->packages),
            'serviceCode' => $this->serviceCode,
            'reference' => $this->reference,
            'description' => $this->description,
            'invoiceNumber' => $this->invoiceNumber,
            'paymentType' => $this->paymentType->value,
            'cashOnDelivery' => $this->cashOnDelivery,
            'codAmount' => $this->codAmount,
            'codCurrency' => $this->codCurrency,
            'shipDate' => $this->shipDate?->format('Y-m-d'),
        ];
    }
}

<?php

declare(strict_types=1);

namespace Omniship\Common;

use Omniship\Common\Enum\DimensionUnit;
use Omniship\Common\Enum\WeightUnit;

class Package
{
    public function __construct(
        public readonly float $weight,
        public readonly WeightUnit $weightUnit = WeightUnit::KILOGRAM,
        public readonly ?float $length = null,
        public readonly ?float $width = null,
        public readonly ?float $height = null,
        public readonly DimensionUnit $dimensionUnit = DimensionUnit::CENTIMETER,
        public readonly ?float $desi = null,
        public readonly ?string $description = null,
        public readonly ?float $insuredValue = null,
        public readonly ?string $currency = null,
        public readonly int $quantity = 1,
    ) {}

    public function getDesi(): ?float
    {
        if ($this->desi !== null) {
            return $this->desi;
        }

        if ($this->length !== null && $this->width !== null && $this->height !== null) {
            return ($this->length * $this->width * $this->height) / 3000;
        }

        return null;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter(get_object_vars($this), fn ($v) => $v !== null);
    }
}

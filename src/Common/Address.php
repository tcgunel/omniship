<?php

declare(strict_types=1);

namespace Omniship\Common;

class Address
{
    public function __construct(
        public readonly ?string $name = null,
        public readonly ?string $company = null,
        public readonly ?string $street1 = null,
        public readonly ?string $street2 = null,
        public readonly ?string $city = null,
        public readonly ?string $district = null,
        public readonly ?string $state = null,
        public readonly ?string $postalCode = null,
        public readonly ?string $country = null,
        public readonly ?string $phone = null,
        public readonly ?string $email = null,
        public readonly bool $residential = false,
        public readonly ?string $taxId = null,
        public readonly ?string $nationalId = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter(get_object_vars($this), fn ($v) => $v !== null && $v !== false);
    }
}

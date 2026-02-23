<?php

declare(strict_types=1);

namespace Omniship\Common;

use Omniship\Common\Enum\ShipmentStatus;

readonly class TrackingEvent
{
    public function __construct(
        public ShipmentStatus $status,
        public string $description,
        public \DateTimeImmutable $occurredAt,
        public ?string $location = null,
        public ?string $city = null,
        public ?string $country = null,
    ) {}
}

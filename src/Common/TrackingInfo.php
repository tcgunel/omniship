<?php

declare(strict_types=1);

namespace Omniship\Common;

use Omniship\Common\Enum\ShipmentStatus;

readonly class TrackingInfo
{
    /**
     * @param TrackingEvent[] $events
     */
    public function __construct(
        public string $trackingNumber,
        public ShipmentStatus $status,
        public array $events = [],
        public ?string $carrier = null,
        public ?string $serviceName = null,
        public ?\DateTimeImmutable $estimatedDelivery = null,
        public ?string $signedBy = null,
    ) {}
}

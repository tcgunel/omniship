<?php

declare(strict_types=1);

namespace Omniship\Common\Message;

use Omniship\Common\Label;

interface ShipmentResponse extends ResponseInterface
{
    public function getShipmentId(): ?string;

    public function getTrackingNumber(): ?string;

    public function getBarcode(): ?string;

    public function getLabel(): ?Label;

    public function getTotalCharge(): ?float;

    public function getCurrency(): ?string;
}

<?php

declare(strict_types=1);

namespace Omniship\Common;

use Omniship\Common\Message\RequestInterface;

/**
 * @method RequestInterface createShipment(array<string, mixed> $options = [])
 * @method RequestInterface getTrackingStatus(array<string, mixed> $options = [])
 * @method RequestInterface cancelShipment(array<string, mixed> $options = [])
 * @method RequestInterface getRates(array<string, mixed> $options = [])
 * @method RequestInterface validateAddress(array<string, mixed> $options = [])
 */
interface CarrierInterface
{
    public function getName(): string;

    public function getShortName(): string;

    /**
     * @return array<string, mixed>
     */
    public function getDefaultParameters(): array;

    /**
     * @param array<string, mixed> $parameters
     */
    public function initialize(array $parameters = []): static;

    /**
     * @return array<string, mixed>
     */
    public function getParameters(): array;
}

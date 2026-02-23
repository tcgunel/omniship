<?php

declare(strict_types=1);

namespace Omniship\Common\Message;

use Omniship\Common\Address;
use Omniship\Common\Exception\RuntimeException;
use Omniship\Common\Package;
use Omniship\Common\ParametersTrait;
use Omniship\Common\Shipment;

abstract class AbstractRequest implements RequestInterface
{
    use ParametersTrait {
        initialize as traitInitialize;
    }

    protected ?ResponseInterface $response = null;

    /**
     * @param array<string, mixed> $parameters
     */
    public function initialize(array $parameters = []): static
    {
        if ($this->response !== null) {
            throw new RuntimeException('Request cannot be modified after it has been sent.');
        }

        $this->traitInitialize($parameters);

        return $this;
    }

    public function getResponse(): ResponseInterface
    {
        if ($this->response === null) {
            throw new RuntimeException('Request has not been sent yet.');
        }

        return $this->response;
    }

    public function send(): ResponseInterface
    {
        $data = $this->getData();

        return $this->sendData($data);
    }

    /**
     * @return array<string, mixed>
     */
    abstract public function getData(): array;

    // -- Common parameter accessors --

    public function getTestMode(): bool
    {
        return (bool) $this->getParameter('testMode');
    }

    public function setTestMode(bool $value): static
    {
        return $this->setParameter('testMode', $value);
    }

    public function getShipment(): ?Shipment
    {
        return $this->getParameter('shipment');
    }

    public function setShipment(Shipment $shipment): static
    {
        return $this->setParameter('shipment', $shipment);
    }

    public function getShipFrom(): ?Address
    {
        return $this->getParameter('shipFrom');
    }

    public function setShipFrom(Address $address): static
    {
        return $this->setParameter('shipFrom', $address);
    }

    public function getShipTo(): ?Address
    {
        return $this->getParameter('shipTo');
    }

    public function setShipTo(Address $address): static
    {
        return $this->setParameter('shipTo', $address);
    }

    /**
     * @return Package[]|null
     */
    public function getPackages(): ?array
    {
        return $this->getParameter('packages');
    }

    /**
     * @param Package[] $packages
     */
    public function setPackages(array $packages): static
    {
        return $this->setParameter('packages', $packages);
    }

    public function getTrackingNumber(): ?string
    {
        return $this->getParameter('trackingNumber');
    }

    public function setTrackingNumber(string $trackingNumber): static
    {
        return $this->setParameter('trackingNumber', $trackingNumber);
    }

    public function getShipmentId(): ?string
    {
        return $this->getParameter('shipmentId');
    }

    public function setShipmentId(string $shipmentId): static
    {
        return $this->setParameter('shipmentId', $shipmentId);
    }

    public function getServiceCode(): ?string
    {
        return $this->getParameter('serviceCode');
    }

    public function setServiceCode(string $serviceCode): static
    {
        return $this->setParameter('serviceCode', $serviceCode);
    }
}

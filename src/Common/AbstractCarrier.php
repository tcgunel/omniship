<?php

declare(strict_types=1);

namespace Omniship\Common;

use Omniship\Common\Exception\BadMethodCallException;
use Omniship\Common\Message\RequestInterface;

abstract class AbstractCarrier implements CarrierInterface
{
    use ParametersTrait;

    public function getShortName(): string
    {
        return Helper::getCarrierShortName(static::class);
    }

    /**
     * @return array<string, mixed>
     */
    public function getDefaultParameters(): array
    {
        return [];
    }

    public function supports(string $method): bool
    {
        return method_exists($this, $method);
    }

    public function getTestMode(): bool
    {
        return (bool) $this->getParameter('testMode');
    }

    public function setTestMode(bool $testMode): static
    {
        return $this->setParameter('testMode', $testMode);
    }

    /**
     * @param class-string<RequestInterface> $class
     * @param array<string, mixed> $parameters
     */
    abstract protected function createRequest(string $class, array $parameters): RequestInterface;

    /**
     * @param array<mixed> $arguments
     */
    public function __call(string $name, array $arguments): mixed
    {
        throw new BadMethodCallException(
            sprintf('Carrier %s does not support the %s() method.', $this->getName(), $name),
        );
    }
}

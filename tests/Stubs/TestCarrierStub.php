<?php

declare(strict_types=1);

use Omniship\Common\AbstractCarrier;
use Omniship\Common\Message\RequestInterface;

class TestCarrierStub extends AbstractCarrier
{
    public function getName(): string
    {
        return 'Test Carrier';
    }

    public function getShortName(): string
    {
        return 'Test';
    }

    /** @return array<string, mixed> */
    public function getDefaultParameters(): array
    {
        return [
            'apiKey' => '',
            'testMode' => false,
        ];
    }

    protected function createRequest(string $class, array $parameters): RequestInterface
    {
        throw new \RuntimeException('Not implemented in stub');
    }
}

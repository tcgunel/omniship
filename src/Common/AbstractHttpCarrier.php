<?php

declare(strict_types=1);

namespace Omniship\Common;

use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Omniship\Common\Message\RequestInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

abstract class AbstractHttpCarrier extends AbstractCarrier
{
    protected ClientInterface $httpClient;
    protected RequestFactoryInterface $requestFactory;
    protected StreamFactoryInterface $streamFactory;

    public function __construct(
        ?ClientInterface $httpClient = null,
        ?RequestFactoryInterface $requestFactory = null,
        ?StreamFactoryInterface $streamFactory = null,
    ) {
        $this->httpClient = $httpClient ?? Psr18ClientDiscovery::find();
        $this->requestFactory = $requestFactory ?? Psr17FactoryDiscovery::findRequestFactory();
        $this->streamFactory = $streamFactory ?? Psr17FactoryDiscovery::findStreamFactory();
        $this->initialize();
    }

    public function getHttpClient(): ClientInterface
    {
        return $this->httpClient;
    }

    public function getRequestFactory(): RequestFactoryInterface
    {
        return $this->requestFactory;
    }

    public function getStreamFactory(): StreamFactoryInterface
    {
        return $this->streamFactory;
    }

    /**
     * @param class-string<RequestInterface> $class
     * @param array<string, mixed> $parameters
     */
    protected function createRequest(string $class, array $parameters): RequestInterface
    {
        /** @var \Omniship\Common\Message\AbstractHttpRequest $request */
        $request = new $class(
            $this->httpClient,
            $this->requestFactory,
            $this->streamFactory,
        );

        return $request->initialize(
            array_replace($this->getParameters(), $parameters),
        );
    }
}

<?php

declare(strict_types=1);

namespace Omniship\Common\Message;

use Omniship\Common\Exception\HttpException;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;

abstract class AbstractHttpRequest extends AbstractRequest
{
    public function __construct(
        protected readonly ClientInterface $httpClient,
        protected readonly RequestFactoryInterface $requestFactory,
        protected readonly StreamFactoryInterface $streamFactory,
    ) {}

    /**
     * @param array<string, string> $headers
     *
     * @throws HttpException
     */
    protected function sendHttpRequest(
        string $method,
        string $url,
        array $headers = [],
        ?string $body = null,
    ): PsrResponseInterface {
        $request = $this->requestFactory->createRequest($method, $url);

        foreach ($headers as $name => $value) {
            $request = $request->withHeader($name, $value);
        }

        if ($body !== null) {
            $stream = $this->streamFactory->createStream($body);
            $request = $request->withBody($stream);
        }

        try {
            return $this->httpClient->sendRequest($request);
        } catch (ClientExceptionInterface $e) {
            throw new HttpException(
                message: 'HTTP request failed: ' . $e->getMessage(),
                previous: $e,
            );
        }
    }
}

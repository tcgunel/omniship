<?php

declare(strict_types=1);

namespace Omniship\Common\Message;

use Omniship\Common\Exception\HttpException;

abstract class AbstractSoapRequest extends AbstractRequest
{
    public function __construct(
        protected readonly \SoapClient $soapClient,
    ) {}

    abstract protected function getSoapMethod(): string;

    /**
     * @param array<string, mixed> $data
     */
    public function sendData(array $data): ResponseInterface
    {
        try {
            $result = $this->soapClient->__soapCall($this->getSoapMethod(), [$data]);

            return $this->response = $this->createResponse($result);
        } catch (\SoapFault $e) {
            throw new HttpException(
                message: 'SOAP request failed: ' . $e->getMessage(),
                previous: $e,
            );
        }
    }

    abstract protected function createResponse(mixed $data): ResponseInterface;
}

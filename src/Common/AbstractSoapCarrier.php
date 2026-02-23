<?php

declare(strict_types=1);

namespace Omniship\Common;

use Omniship\Common\Message\RequestInterface;

abstract class AbstractSoapCarrier extends AbstractCarrier
{
    protected ?\SoapClient $soapClient = null;

    public function __construct(?\SoapClient $soapClient = null)
    {
        $this->soapClient = $soapClient;
        $this->initialize();
    }

    abstract protected function getWsdlUrl(): string;

    /**
     * @return array<string, mixed>
     */
    protected function getSoapOptions(): array
    {
        return [
            'trace' => true,
            'exceptions' => true,
            'encoding' => 'UTF-8',
            'soap_version' => SOAP_1_1,
            'cache_wsdl' => WSDL_CACHE_BOTH,
        ];
    }

    public function getSoapClient(): \SoapClient
    {
        if ($this->soapClient === null) {
            $this->soapClient = new \SoapClient(
                $this->getWsdlUrl(),
                $this->getSoapOptions(),
            );
        }

        return $this->soapClient;
    }

    public function setSoapClient(\SoapClient $client): static
    {
        $this->soapClient = $client;

        return $this;
    }

    /**
     * @param class-string<RequestInterface> $class
     * @param array<string, mixed> $parameters
     */
    protected function createRequest(string $class, array $parameters): RequestInterface
    {
        /** @var \Omniship\Common\Message\AbstractSoapRequest $request */
        $request = new $class($this->getSoapClient());

        return $request->initialize(
            array_replace($this->getParameters(), $parameters),
        );
    }
}

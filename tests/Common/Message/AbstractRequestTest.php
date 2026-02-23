<?php

declare(strict_types=1);

use Omniship\Common\Address;
use Omniship\Common\Exception\RuntimeException;
use Omniship\Common\Message\AbstractSoapRequest;
use Omniship\Common\Message\AbstractResponse;
use Omniship\Common\Message\ResponseInterface;
use Omniship\Common\Package;

// Concrete stub for testing
class TestSoapRequestStub extends AbstractSoapRequest
{
    protected function getSoapMethod(): string
    {
        return 'testMethod';
    }

    public function getData(): array
    {
        return ['test' => 'data'];
    }

    protected function createResponse(mixed $data): ResponseInterface
    {
        return new class ($this, $data) extends AbstractResponse {
            public function isSuccessful(): bool
            {
                return true;
            }
        };
    }
}

it('initializes with parameters', function () {
    $client = $this->createMock(SoapClient::class);
    $request = new TestSoapRequestStub($client);
    $request->initialize(['testMode' => true, 'trackingNumber' => 'ABC123']);

    expect($request->getTestMode())->toBeTrue();
    expect($request->getTrackingNumber())->toBe('ABC123');
});

it('throws when accessing response before send', function () {
    $client = $this->createMock(SoapClient::class);
    $request = new TestSoapRequestStub($client);

    expect(fn () => $request->getResponse())->toThrow(
        RuntimeException::class,
        'Request has not been sent yet',
    );
});

it('prevents modification after send', function () {
    $client = $this->createMock(SoapClient::class);
    $client->method('__soapCall')->willReturn(['status' => 'ok']);

    $request = new TestSoapRequestStub($client);
    $request->initialize();
    $request->send();

    expect(fn () => $request->initialize(['testMode' => true]))->toThrow(
        RuntimeException::class,
        'Request cannot be modified after it has been sent',
    );
});

it('gets and sets common parameters', function () {
    $client = $this->createMock(SoapClient::class);
    $request = new TestSoapRequestStub($client);
    $request->initialize();

    $address = new Address(name: 'Test', city: 'İstanbul');
    $request->setShipFrom($address);
    expect($request->getShipFrom())->toBe($address);

    $request->setShipTo($address);
    expect($request->getShipTo())->toBe($address);

    $packages = [new Package(weight: 1.0)];
    $request->setPackages($packages);
    expect($request->getPackages())->toBe($packages);

    $request->setTrackingNumber('TR123');
    expect($request->getTrackingNumber())->toBe('TR123');

    $request->setShipmentId('SHIP-001');
    expect($request->getShipmentId())->toBe('SHIP-001');

    $request->setServiceCode('express');
    expect($request->getServiceCode())->toBe('express');
});

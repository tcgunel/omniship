<?php

declare(strict_types=1);

use Omniship\Common\Exception\BadMethodCallException;

it('initializes with default parameters', function () {
    $carrier = new TestCarrierStub();
    $carrier->initialize();

    expect($carrier->getParameters())->toHaveKey('apiKey', '');
    expect($carrier->getParameters())->toHaveKey('testMode', false);
});

it('initializes with custom parameters', function () {
    $carrier = new TestCarrierStub();
    $carrier->initialize(['apiKey' => 'my-key', 'testMode' => true]);

    expect($carrier->getParameters()['apiKey'])->toBe('my-key');
    expect($carrier->getTestMode())->toBeTrue();
});

it('reports supported methods', function () {
    $carrier = new TestCarrierStub();

    expect($carrier->supports('getName'))->toBeTrue();
    expect($carrier->supports('createShipment'))->toBeFalse();
});

it('throws on unsupported method call', function () {
    $carrier = new TestCarrierStub();

    expect(fn () => $carrier->createShipment())->toThrow(
        BadMethodCallException::class,
        'does not support the createShipment() method',
    );
});

it('gets and sets test mode', function () {
    $carrier = new TestCarrierStub();
    $carrier->initialize();

    expect($carrier->getTestMode())->toBeFalse();

    $carrier->setTestMode(true);

    expect($carrier->getTestMode())->toBeTrue();
});

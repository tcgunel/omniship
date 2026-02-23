<?php

declare(strict_types=1);

use Omniship\Common\CarrierFactory;
use Omniship\Omniship;

it('returns a carrier factory', function () {
    expect(Omniship::getFactory())->toBeInstanceOf(CarrierFactory::class);
});

it('allows setting a custom factory', function () {
    $factory = new CarrierFactory();
    $factory->register('Test', TestCarrierStub::class);

    Omniship::setFactory($factory);

    expect(Omniship::getFactory()->all())->toHaveKey('Test');
});

it('creates a carrier by registered name', function () {
    $factory = new CarrierFactory();
    $factory->register('Test', TestCarrierStub::class);
    Omniship::setFactory($factory);

    $carrier = Omniship::create('Test');

    expect($carrier)->toBeInstanceOf(TestCarrierStub::class);
    expect($carrier->getName())->toBe('Test Carrier');
});

it('creates a carrier by FQCN', function () {
    Omniship::setFactory(new CarrierFactory());

    $carrier = Omniship::create(TestCarrierStub::class);

    expect($carrier)->toBeInstanceOf(TestCarrierStub::class);
});

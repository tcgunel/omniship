<?php

declare(strict_types=1);

use Omniship\Common\CarrierFactory;
use Omniship\Common\Exception\RuntimeException;

it('registers and creates carriers', function () {
    $factory = new CarrierFactory();
    $factory->register('TestCarrier', TestCarrierStub::class);

    $carrier = $factory->create('TestCarrier');

    expect($carrier)->toBeInstanceOf(TestCarrierStub::class);
    expect($carrier->getName())->toBe('Test Carrier');
});

it('lists all registered carriers', function () {
    $factory = new CarrierFactory();
    $factory->register('A', TestCarrierStub::class);
    $factory->register('B', TestCarrierStub::class);

    expect($factory->all())->toHaveCount(2);
    expect($factory->all())->toHaveKeys(['A', 'B']);
});

it('replaces all carriers', function () {
    $factory = new CarrierFactory();
    $factory->register('A', TestCarrierStub::class);
    $factory->replace(['B' => TestCarrierStub::class]);

    expect($factory->all())->toHaveCount(1);
    expect($factory->all())->toHaveKey('B');
});

it('resolves FQCN directly', function () {
    $factory = new CarrierFactory();
    $carrier = $factory->create(TestCarrierStub::class);

    expect($carrier)->toBeInstanceOf(TestCarrierStub::class);
});

it('throws on unknown carrier', function () {
    $factory = new CarrierFactory();

    expect(fn () => $factory->create('NonExistent'))->toThrow(
        RuntimeException::class,
        'Carrier class',
    );
});

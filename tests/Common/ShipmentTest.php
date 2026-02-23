<?php

declare(strict_types=1);

use Omniship\Common\Address;
use Omniship\Common\Enum\PaymentType;
use Omniship\Common\Package;
use Omniship\Common\Shipment;

it('can be constructed with minimal arguments', function () {
    $shipment = new Shipment(
        shipFrom: new Address(city: 'İstanbul', country: 'TR'),
        shipTo: new Address(city: 'Ankara', country: 'TR'),
        packages: [new Package(weight: 1.0)],
    );

    expect($shipment->shipFrom->city)->toBe('İstanbul');
    expect($shipment->shipTo->city)->toBe('Ankara');
    expect($shipment->packages)->toHaveCount(1);
    expect($shipment->paymentType)->toBe(PaymentType::SENDER);
    expect($shipment->cashOnDelivery)->toBeFalse();
});

it('supports cash on delivery', function () {
    $shipment = new Shipment(
        shipFrom: new Address(),
        shipTo: new Address(),
        packages: [new Package(weight: 1.0)],
        cashOnDelivery: true,
        codAmount: 150.0,
        codCurrency: 'TRY',
    );

    expect($shipment->cashOnDelivery)->toBeTrue();
    expect($shipment->codAmount)->toBe(150.0);
    expect($shipment->codCurrency)->toBe('TRY');
});

it('converts to array', function () {
    $shipment = new Shipment(
        shipFrom: new Address(name: 'Sender', city: 'İstanbul', country: 'TR'),
        shipTo: new Address(name: 'Receiver', city: 'Ankara', country: 'TR'),
        packages: [new Package(weight: 2.0)],
        reference: 'ORDER-123',
    );

    $array = $shipment->toArray();

    expect($array['shipFrom'])->toHaveKey('name', 'Sender');
    expect($array['shipTo'])->toHaveKey('name', 'Receiver');
    expect($array['packages'])->toHaveCount(1);
    expect($array['reference'])->toBe('ORDER-123');
    expect($array['paymentType'])->toBe('sender');
});

it('formats ship date correctly', function () {
    $shipment = new Shipment(
        shipFrom: new Address(),
        shipTo: new Address(),
        packages: [new Package(weight: 1.0)],
        shipDate: new DateTimeImmutable('2026-03-15'),
    );

    $array = $shipment->toArray();

    expect($array['shipDate'])->toBe('2026-03-15');
});

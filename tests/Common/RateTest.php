<?php

declare(strict_types=1);

use Omniship\Common\Rate;

it('can be constructed with all properties', function () {
    $rate = new Rate(
        carrier: 'UPS',
        serviceCode: '03',
        serviceName: 'UPS Ground',
        totalPrice: 12.50,
        currency: 'USD',
        transitDays: 5,
    );

    expect($rate->carrier)->toBe('UPS');
    expect($rate->serviceCode)->toBe('03');
    expect($rate->serviceName)->toBe('UPS Ground');
    expect($rate->totalPrice)->toBe(12.50);
    expect($rate->currency)->toBe('USD');
    expect($rate->transitDays)->toBe(5);
});

it('has null defaults for optional fields', function () {
    $rate = new Rate(
        carrier: 'Yurtici',
        serviceCode: 'standard',
        serviceName: 'Standart Teslimat',
        totalPrice: 45.0,
        currency: 'TRY',
    );

    expect($rate->transitDays)->toBeNull();
    expect($rate->estimatedDelivery)->toBeNull();
});

it('is readonly', function () {
    $rate = new Rate(
        carrier: 'Test',
        serviceCode: 'test',
        serviceName: 'Test',
        totalPrice: 10.0,
        currency: 'TRY',
    );

    // Readonly classes cannot have properties reassigned
    expect(fn () => $rate->carrier = 'Changed')->toThrow(Error::class); // @phpstan-ignore-line
});

<?php

declare(strict_types=1);

use Omniship\Common\Address;

it('can be constructed with named arguments', function () {
    $address = new Address(
        name: 'Ahmet Yılmaz',
        street1: 'Atatürk Cad. No:42',
        city: 'İstanbul',
        district: 'Kadıköy',
        postalCode: '34710',
        country: 'TR',
        phone: '+905551234567',
    );

    expect($address->name)->toBe('Ahmet Yılmaz');
    expect($address->street1)->toBe('Atatürk Cad. No:42');
    expect($address->city)->toBe('İstanbul');
    expect($address->district)->toBe('Kadıköy');
    expect($address->postalCode)->toBe('34710');
    expect($address->country)->toBe('TR');
    expect($address->phone)->toBe('+905551234567');
});

it('has null defaults for optional fields', function () {
    $address = new Address();

    expect($address->name)->toBeNull();
    expect($address->company)->toBeNull();
    expect($address->street1)->toBeNull();
    expect($address->street2)->toBeNull();
    expect($address->city)->toBeNull();
    expect($address->district)->toBeNull();
    expect($address->state)->toBeNull();
    expect($address->postalCode)->toBeNull();
    expect($address->country)->toBeNull();
    expect($address->phone)->toBeNull();
    expect($address->email)->toBeNull();
    expect($address->residential)->toBeFalse();
    expect($address->taxId)->toBeNull();
    expect($address->nationalId)->toBeNull();
});

it('converts to array excluding null and false values', function () {
    $address = new Address(
        name: 'Test',
        city: 'İstanbul',
        country: 'TR',
    );

    $array = $address->toArray();

    expect($array)->toBe([
        'name' => 'Test',
        'city' => 'İstanbul',
        'country' => 'TR',
    ]);
});

it('includes residential when true', function () {
    $address = new Address(name: 'Test', residential: true);

    $array = $address->toArray();

    expect($array)->toHaveKey('residential');
    expect($array['residential'])->toBeTrue();
});

it('supports Turkish identity fields', function () {
    $address = new Address(
        name: 'Test Corp',
        taxId: '1234567890',
        nationalId: '12345678901',
    );

    expect($address->taxId)->toBe('1234567890');
    expect($address->nationalId)->toBe('12345678901');
});

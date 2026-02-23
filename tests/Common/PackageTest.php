<?php

declare(strict_types=1);

use Omniship\Common\Enum\DimensionUnit;
use Omniship\Common\Enum\WeightUnit;
use Omniship\Common\Package;

it('can be constructed with defaults', function () {
    $package = new Package(weight: 2.5);

    expect($package->weight)->toBe(2.5);
    expect($package->weightUnit)->toBe(WeightUnit::KILOGRAM);
    expect($package->dimensionUnit)->toBe(DimensionUnit::CENTIMETER);
    expect($package->quantity)->toBe(1);
    expect($package->length)->toBeNull();
    expect($package->desi)->toBeNull();
});

it('calculates desi from dimensions', function () {
    $package = new Package(
        weight: 1.0,
        length: 30,
        width: 20,
        height: 10,
    );

    // (30 * 20 * 10) / 3000 = 2.0
    expect($package->getDesi())->toBe(2.0);
});

it('returns explicit desi over calculated', function () {
    $package = new Package(
        weight: 1.0,
        length: 30,
        width: 20,
        height: 10,
        desi: 5.0,
    );

    expect($package->getDesi())->toBe(5.0);
});

it('returns null desi when no dimensions and no explicit desi', function () {
    $package = new Package(weight: 1.0);

    expect($package->getDesi())->toBeNull();
});

it('supports insured value', function () {
    $package = new Package(
        weight: 1.0,
        insuredValue: 100.0,
        currency: 'TRY',
    );

    expect($package->insuredValue)->toBe(100.0);
    expect($package->currency)->toBe('TRY');
});

it('converts to array excluding null values', function () {
    $package = new Package(weight: 2.5, desi: 3.0);

    $array = $package->toArray();

    expect($array)->toHaveKeys(['weight', 'weightUnit', 'dimensionUnit', 'desi', 'quantity']);
    expect($array)->not->toHaveKey('length');
    expect($array)->not->toHaveKey('description');
});

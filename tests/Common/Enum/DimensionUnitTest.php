<?php

declare(strict_types=1);

use Omniship\Common\Enum\DimensionUnit;

it('has correct backing values', function () {
    expect(DimensionUnit::INCH->value)->toBe('IN');
    expect(DimensionUnit::CENTIMETER->value)->toBe('CM');
});

it('can be created from value', function () {
    expect(DimensionUnit::from('CM'))->toBe(DimensionUnit::CENTIMETER);
    expect(DimensionUnit::from('IN'))->toBe(DimensionUnit::INCH);
});

it('has 2 cases', function () {
    expect(DimensionUnit::cases())->toHaveCount(2);
});

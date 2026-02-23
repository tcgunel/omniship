<?php

declare(strict_types=1);

use Omniship\Common\Enum\WeightUnit;

it('has correct backing values', function () {
    expect(WeightUnit::POUND->value)->toBe('LB');
    expect(WeightUnit::KILOGRAM->value)->toBe('KG');
    expect(WeightUnit::OUNCE->value)->toBe('OZ');
    expect(WeightUnit::GRAM->value)->toBe('G');
});

it('can be created from value', function () {
    expect(WeightUnit::from('KG'))->toBe(WeightUnit::KILOGRAM);
    expect(WeightUnit::from('LB'))->toBe(WeightUnit::POUND);
});

it('returns null for invalid value with tryFrom', function () {
    expect(WeightUnit::tryFrom('INVALID'))->toBeNull();
});

it('has 4 cases', function () {
    expect(WeightUnit::cases())->toHaveCount(4);
});

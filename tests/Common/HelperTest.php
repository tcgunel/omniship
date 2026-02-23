<?php

declare(strict_types=1);

use Omniship\Common\Helper;

it('converts strings to camelCase', function () {
    expect(Helper::camelCase('test_string'))->toBe('testString');
    expect(Helper::camelCase('test-string'))->toBe('testString');
    expect(Helper::camelCase('TestString'))->toBe('testString');
});

it('extracts carrier short name from FQCN', function () {
    expect(Helper::getCarrierShortName('Omniship\UPS\Carrier'))->toBe('UPS');
    expect(Helper::getCarrierShortName('Omniship\Yurtici\Carrier'))->toBe('Yurtici');
    expect(Helper::getCarrierShortName('Omniship\DHL\Express\Carrier'))->toBe('DHL_Express');
});

it('returns class name when pattern does not match', function () {
    expect(Helper::getCarrierShortName('SomeOtherClass'))->toBe('SomeOtherClass');
});

it('resolves short name to FQCN', function () {
    expect(Helper::getCarrierClassName('UPS'))->toBe('Omniship\UPS\Carrier');
    expect(Helper::getCarrierClassName('Yurtici'))->toBe('Omniship\Yurtici\Carrier');
    expect(Helper::getCarrierClassName('DHL_Express'))->toBe('Omniship\DHL\Express\Carrier');
});

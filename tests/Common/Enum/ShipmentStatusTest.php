<?php

declare(strict_types=1);

use Omniship\Common\Enum\ShipmentStatus;

it('has correct backing values', function () {
    expect(ShipmentStatus::PRE_TRANSIT->value)->toBe('pre_transit');
    expect(ShipmentStatus::PICKED_UP->value)->toBe('picked_up');
    expect(ShipmentStatus::IN_TRANSIT->value)->toBe('in_transit');
    expect(ShipmentStatus::OUT_FOR_DELIVERY->value)->toBe('out_for_delivery');
    expect(ShipmentStatus::DELIVERED->value)->toBe('delivered');
    expect(ShipmentStatus::RETURNED->value)->toBe('returned');
    expect(ShipmentStatus::FAILURE->value)->toBe('failure');
    expect(ShipmentStatus::CANCELLED->value)->toBe('cancelled');
    expect(ShipmentStatus::UNKNOWN->value)->toBe('unknown');
});

it('has 9 cases', function () {
    expect(ShipmentStatus::cases())->toHaveCount(9);
});

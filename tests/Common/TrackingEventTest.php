<?php

declare(strict_types=1);

use Omniship\Common\Enum\ShipmentStatus;
use Omniship\Common\TrackingEvent;

it('can be constructed with all fields', function () {
    $event = new TrackingEvent(
        status: ShipmentStatus::IN_TRANSIT,
        description: 'Aktarmada',
        occurredAt: new DateTimeImmutable('2026-02-23 14:30:00'),
        location: 'İstanbul Transfer Merkezi',
        city: 'İstanbul',
        country: 'TR',
    );

    expect($event->status)->toBe(ShipmentStatus::IN_TRANSIT);
    expect($event->description)->toBe('Aktarmada');
    expect($event->city)->toBe('İstanbul');
    expect($event->country)->toBe('TR');
});

it('has null defaults for optional location fields', function () {
    $event = new TrackingEvent(
        status: ShipmentStatus::DELIVERED,
        description: 'Teslim edildi',
        occurredAt: new DateTimeImmutable(),
    );

    expect($event->location)->toBeNull();
    expect($event->city)->toBeNull();
    expect($event->country)->toBeNull();
});

<?php

declare(strict_types=1);

use Omniship\Common\Enum\ShipmentStatus;
use Omniship\Common\TrackingEvent;
use Omniship\Common\TrackingInfo;

it('can be constructed with tracking number and status', function () {
    $info = new TrackingInfo(
        trackingNumber: '3300123456789',
        status: ShipmentStatus::IN_TRANSIT,
    );

    expect($info->trackingNumber)->toBe('3300123456789');
    expect($info->status)->toBe(ShipmentStatus::IN_TRANSIT);
    expect($info->events)->toBe([]);
    expect($info->carrier)->toBeNull();
});

it('holds an array of tracking events', function () {
    $events = [
        new TrackingEvent(
            status: ShipmentStatus::PICKED_UP,
            description: 'Kabul edildi',
            occurredAt: new DateTimeImmutable('2026-02-22 10:00:00'),
            city: 'İstanbul',
        ),
        new TrackingEvent(
            status: ShipmentStatus::IN_TRANSIT,
            description: 'Aktarmada',
            occurredAt: new DateTimeImmutable('2026-02-22 18:00:00'),
            city: 'Ankara',
        ),
        new TrackingEvent(
            status: ShipmentStatus::DELIVERED,
            description: 'Teslim edildi',
            occurredAt: new DateTimeImmutable('2026-02-23 11:00:00'),
            city: 'Ankara',
        ),
    ];

    $info = new TrackingInfo(
        trackingNumber: '3300123456789',
        status: ShipmentStatus::DELIVERED,
        events: $events,
        carrier: 'Yurtici',
        signedBy: 'Mehmet D.',
    );

    expect($info->events)->toHaveCount(3);
    expect($info->carrier)->toBe('Yurtici');
    expect($info->signedBy)->toBe('Mehmet D.');
    expect($info->events[0]->status)->toBe(ShipmentStatus::PICKED_UP);
    expect($info->events[2]->status)->toBe(ShipmentStatus::DELIVERED);
});

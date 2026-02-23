<?php

declare(strict_types=1);

namespace Omniship\Common\Enum;

enum ShipmentStatus: string
{
    case PRE_TRANSIT = 'pre_transit';
    case PICKED_UP = 'picked_up';
    case IN_TRANSIT = 'in_transit';
    case OUT_FOR_DELIVERY = 'out_for_delivery';
    case DELIVERED = 'delivered';
    case RETURNED = 'returned';
    case FAILURE = 'failure';
    case CANCELLED = 'cancelled';
    case UNKNOWN = 'unknown';
}

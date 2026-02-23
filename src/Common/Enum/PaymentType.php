<?php

declare(strict_types=1);

namespace Omniship\Common\Enum;

enum PaymentType: string
{
    case SENDER = 'sender';
    case RECEIVER = 'receiver';
    case THIRD_PARTY = 'third_party';
}

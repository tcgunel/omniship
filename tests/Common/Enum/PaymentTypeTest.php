<?php

declare(strict_types=1);

use Omniship\Common\Enum\PaymentType;

it('has correct backing values', function () {
    expect(PaymentType::SENDER->value)->toBe('sender');
    expect(PaymentType::RECEIVER->value)->toBe('receiver');
    expect(PaymentType::THIRD_PARTY->value)->toBe('third_party');
});

it('has 3 cases', function () {
    expect(PaymentType::cases())->toHaveCount(3);
});

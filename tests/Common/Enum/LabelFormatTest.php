<?php

declare(strict_types=1);

use Omniship\Common\Enum\LabelFormat;

it('has correct backing values', function () {
    expect(LabelFormat::PDF->value)->toBe('PDF');
    expect(LabelFormat::PNG->value)->toBe('PNG');
    expect(LabelFormat::ZPL->value)->toBe('ZPL');
    expect(LabelFormat::EPL->value)->toBe('EPL');
    expect(LabelFormat::GIF->value)->toBe('GIF');
    expect(LabelFormat::HTML->value)->toBe('HTML');
});

it('has 6 cases', function () {
    expect(LabelFormat::cases())->toHaveCount(6);
});

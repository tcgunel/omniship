<?php

declare(strict_types=1);

use Omniship\Common\Enum\LabelFormat;
use Omniship\Common\Label;

it('can be constructed with required fields', function () {
    $label = new Label(
        trackingNumber: '1Z999AA10123456784',
        content: base64_encode('PDF content'),
    );

    expect($label->trackingNumber)->toBe('1Z999AA10123456784');
    expect($label->format)->toBe(LabelFormat::PDF);
    expect($label->barcode)->toBeNull();
    expect($label->shipmentId)->toBeNull();
});

it('supports barcode field for Turkish carriers', function () {
    $label = new Label(
        trackingNumber: '3300123456789',
        content: '<html>label</html>',
        format: LabelFormat::HTML,
        barcode: '3300123456789001',
    );

    expect($label->barcode)->toBe('3300123456789001');
    expect($label->format)->toBe(LabelFormat::HTML);
});

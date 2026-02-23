<?php

declare(strict_types=1);

namespace Omniship\Common;

use Omniship\Common\Enum\LabelFormat;

readonly class Label
{
    public function __construct(
        public string $trackingNumber,
        public string $content,
        public LabelFormat $format = LabelFormat::PDF,
        public ?string $barcode = null,
        public ?string $shipmentId = null,
    ) {}
}

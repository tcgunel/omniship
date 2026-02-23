<?php

declare(strict_types=1);

namespace Omniship\Common\Enum;

enum WeightUnit: string
{
    case POUND = 'LB';
    case KILOGRAM = 'KG';
    case OUNCE = 'OZ';
    case GRAM = 'G';
}

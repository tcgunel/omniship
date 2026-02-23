<?php

declare(strict_types=1);

namespace Omniship\Common\Enum;

enum LabelFormat: string
{
    case PDF = 'PDF';
    case PNG = 'PNG';
    case ZPL = 'ZPL';
    case EPL = 'EPL';
    case GIF = 'GIF';
    case HTML = 'HTML';
}

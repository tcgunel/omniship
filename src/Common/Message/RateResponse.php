<?php

declare(strict_types=1);

namespace Omniship\Common\Message;

use Omniship\Common\Rate;

interface RateResponse extends ResponseInterface
{
    /**
     * @return Rate[]
     */
    public function getRates(): array;
}

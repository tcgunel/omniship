<?php

declare(strict_types=1);

namespace Omniship\Common\Message;

interface CancelResponse extends ResponseInterface
{
    public function isCancelled(): bool;
}

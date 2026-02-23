<?php

declare(strict_types=1);

namespace Omniship\Common\Message;

use Omniship\Common\TrackingInfo;

interface TrackingResponse extends ResponseInterface
{
    public function getTrackingInfo(): TrackingInfo;
}

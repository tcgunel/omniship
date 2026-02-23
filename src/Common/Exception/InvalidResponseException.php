<?php

declare(strict_types=1);

namespace Omniship\Common\Exception;

class InvalidResponseException extends \RuntimeException implements OmnishipException
{
    public function __construct(
        string $message = 'Invalid response from carrier',
        public readonly mixed $responseBody = null,
        int $code = 0,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}

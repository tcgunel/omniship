<?php

declare(strict_types=1);

namespace Omniship\Common\Exception;

class HttpException extends \RuntimeException implements OmnishipException
{
    public function __construct(
        string $message = 'HTTP request failed',
        public readonly ?int $statusCode = null,
        public readonly mixed $responseBody = null,
        int $code = 0,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}

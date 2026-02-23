<?php

declare(strict_types=1);

namespace Omniship\Common\Message;

interface ResponseInterface
{
    public function getRequest(): RequestInterface;

    public function isSuccessful(): bool;

    public function getData(): mixed;

    public function getMessage(): ?string;

    public function getCode(): ?string;
}

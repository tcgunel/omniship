<?php

declare(strict_types=1);

namespace Omniship\Common\Message;

abstract class AbstractResponse implements ResponseInterface
{
    public function __construct(
        protected readonly RequestInterface $request,
        protected readonly mixed $data,
    ) {}

    public function getRequest(): RequestInterface
    {
        return $this->request;
    }

    public function getData(): mixed
    {
        return $this->data;
    }

    public function getMessage(): ?string
    {
        return null;
    }

    public function getCode(): ?string
    {
        return null;
    }
}

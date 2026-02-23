<?php

declare(strict_types=1);

namespace Omniship\Common\Message;

interface RequestInterface
{
    /**
     * @param array<string, mixed> $parameters
     */
    public function initialize(array $parameters = []): static;

    /**
     * @return array<string, mixed>
     */
    public function getParameters(): array;

    public function getResponse(): ResponseInterface;

    public function send(): ResponseInterface;

    /**
     * @param array<string, mixed> $data
     */
    public function sendData(array $data): ResponseInterface;
}

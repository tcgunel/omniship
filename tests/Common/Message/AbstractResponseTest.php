<?php

declare(strict_types=1);

use Omniship\Common\Message\AbstractResponse;
use Omniship\Common\Message\RequestInterface;

it('stores request and data', function () {
    $request = $this->createMock(RequestInterface::class);
    $data = ['status' => 'ok', 'tracking' => '123'];

    $response = new class ($request, $data) extends AbstractResponse {
        public function isSuccessful(): bool
        {
            return $this->data['status'] === 'ok';
        }
    };

    expect($response->getRequest())->toBe($request);
    expect($response->getData())->toBe($data);
    expect($response->isSuccessful())->toBeTrue();
});

it('returns null for message and code by default', function () {
    $request = $this->createMock(RequestInterface::class);

    $response = new class ($request, []) extends AbstractResponse {
        public function isSuccessful(): bool
        {
            return false;
        }
    };

    expect($response->getMessage())->toBeNull();
    expect($response->getCode())->toBeNull();
});

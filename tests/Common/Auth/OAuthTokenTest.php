<?php

declare(strict_types=1);

use Omniship\Common\Auth\OAuthToken;

it('creates a token with all fields', function () {
    $token = new OAuthToken(
        accessToken: 'abc123',
        tokenType: 'Bearer',
        expiresIn: 3600,
        issuedAt: new DateTimeImmutable(),
        scope: 'read write',
    );

    expect($token->accessToken)->toBe('abc123');
    expect($token->tokenType)->toBe('Bearer');
    expect($token->expiresIn)->toBe(3600);
    expect($token->scope)->toBe('read write');
});

it('generates bearer header', function () {
    $token = new OAuthToken(
        accessToken: 'my-token',
        tokenType: 'Bearer',
        expiresIn: 3600,
        issuedAt: new DateTimeImmutable(),
    );

    expect($token->getBearerHeader())->toBe('Bearer my-token');
});

it('detects unexpired token', function () {
    $token = new OAuthToken(
        accessToken: 'test',
        tokenType: 'Bearer',
        expiresIn: 3600,
        issuedAt: new DateTimeImmutable(),
    );

    expect($token->isExpired())->toBeFalse();
});

it('detects expired token', function () {
    $token = new OAuthToken(
        accessToken: 'test',
        tokenType: 'Bearer',
        expiresIn: 10,
        issuedAt: new DateTimeImmutable('-1 hour'),
    );

    expect($token->isExpired())->toBeTrue();
});

it('respects buffer seconds for expiry', function () {
    // Token issued now, expires in 30 seconds, buffer is 60 seconds
    $token = new OAuthToken(
        accessToken: 'test',
        tokenType: 'Bearer',
        expiresIn: 30,
        issuedAt: new DateTimeImmutable(),
    );

    // With 60s buffer, 30s remaining is considered expired
    expect($token->isExpired(bufferSeconds: 60))->toBeTrue();

    // With 0s buffer, 30s remaining is NOT expired
    expect($token->isExpired(bufferSeconds: 0))->toBeFalse();
});

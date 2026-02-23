<?php

declare(strict_types=1);

namespace Omniship\Common\Auth;

use Omniship\Common\Exception\HttpException;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

trait OAuthTrait
{
    protected ?OAuthToken $oauthToken = null;

    abstract protected function getOAuthTokenUrl(): string;

    abstract protected function getHttpClient(): ClientInterface;

    abstract protected function getRequestFactory(): RequestFactoryInterface;

    abstract protected function getStreamFactory(): StreamFactoryInterface;

    public function getClientId(): ?string
    {
        return $this->getParameter('clientId');
    }

    public function setClientId(string $clientId): static
    {
        return $this->setParameter('clientId', $clientId);
    }

    public function getClientSecret(): ?string
    {
        return $this->getParameter('clientSecret');
    }

    public function setClientSecret(string $clientSecret): static
    {
        return $this->setParameter('clientSecret', $clientSecret);
    }

    protected function getOAuthToken(): OAuthToken
    {
        if ($this->oauthToken === null || $this->oauthToken->isExpired()) {
            $this->oauthToken = $this->fetchOAuthToken();
        }

        return $this->oauthToken;
    }

    protected function fetchOAuthToken(): OAuthToken
    {
        $credentials = base64_encode($this->getClientId() . ':' . $this->getClientSecret());

        $request = $this->getRequestFactory()->createRequest('POST', $this->getOAuthTokenUrl());
        $request = $request
            ->withHeader('Authorization', 'Basic ' . $credentials)
            ->withHeader('Content-Type', 'application/x-www-form-urlencoded');

        $body = $this->getStreamFactory()->createStream('grant_type=client_credentials');
        $request = $request->withBody($body);

        try {
            $response = $this->getHttpClient()->sendRequest($request);
        } catch (\Throwable $e) {
            throw new HttpException(
                message: 'OAuth token request failed: ' . $e->getMessage(),
                previous: $e,
            );
        }

        $statusCode = $response->getStatusCode();
        $responseBody = (string) $response->getBody();

        if ($statusCode !== 200) {
            throw new HttpException(
                message: 'OAuth token request returned HTTP ' . $statusCode,
                statusCode: $statusCode,
                responseBody: $responseBody,
            );
        }

        /** @var array{access_token: string, token_type?: string, expires_in?: int, scope?: string} $data */
        $data = json_decode($responseBody, true, 512, JSON_THROW_ON_ERROR);

        return new OAuthToken(
            accessToken: $data['access_token'],
            tokenType: $data['token_type'] ?? 'Bearer',
            expiresIn: (int) ($data['expires_in'] ?? 3600),
            issuedAt: new \DateTimeImmutable(),
            scope: $data['scope'] ?? null,
        );
    }
}

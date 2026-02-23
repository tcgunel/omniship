<?php

declare(strict_types=1);

namespace Omniship\Common\Auth;

readonly class OAuthToken
{
    public function __construct(
        public string $accessToken,
        public string $tokenType,
        public int $expiresIn,
        public \DateTimeImmutable $issuedAt,
        public ?string $scope = null,
    ) {}

    public function isExpired(int $bufferSeconds = 60): bool
    {
        $expiresAt = $this->issuedAt->modify("+{$this->expiresIn} seconds");
        $now = new \DateTimeImmutable();

        return $now >= $expiresAt->modify("-{$bufferSeconds} seconds");
    }

    public function getBearerHeader(): string
    {
        return 'Bearer ' . $this->accessToken;
    }
}

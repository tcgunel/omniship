<?php

declare(strict_types=1);

namespace Omniship\Common\Auth;

trait BasicAuthTrait
{
    public function getUsername(): ?string
    {
        return $this->getParameter('username');
    }

    public function setUsername(string $username): static
    {
        return $this->setParameter('username', $username);
    }

    public function getPassword(): ?string
    {
        return $this->getParameter('password');
    }

    public function setPassword(string $password): static
    {
        return $this->setParameter('password', $password);
    }

    protected function getBasicAuthHeader(): string
    {
        return 'Basic ' . base64_encode($this->getUsername() . ':' . $this->getPassword());
    }
}

<?php

declare(strict_types=1);

namespace Omniship\Common;

use Omniship\Common\Exception\InvalidRequestException;

trait ParametersTrait
{
    /** @var array<string, mixed> */
    protected array $parameters = [];

    /**
     * @param array<string, mixed> $parameters
     */
    public function initialize(array $parameters = []): static
    {
        $this->parameters = [];

        /** @var array<string, mixed> $defaults */
        $defaults = method_exists($this, 'getDefaultParameters')
            ? $this->getDefaultParameters()
            : [];

        $merged = array_replace($defaults, $parameters);

        foreach ($merged as $key => $value) {
            $setter = 'set' . ucfirst($key);
            if (method_exists($this, $setter)) {
                $this->$setter($value);
            } else {
                $this->parameters[$key] = $value;
            }
        }

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    protected function getParameter(string $key): mixed
    {
        return $this->parameters[$key] ?? null;
    }

    protected function setParameter(string $key, mixed $value): static
    {
        $this->parameters[$key] = $value;

        return $this;
    }

    /**
     * @throws InvalidRequestException
     */
    public function validate(string ...$args): void
    {
        foreach ($args as $key) {
            $value = $this->parameters[$key] ?? null;
            if ($value === null || $value === '' || $value === []) {
                throw new InvalidRequestException("The $key parameter is required");
            }
        }
    }
}

<?php

declare(strict_types=1);

namespace Omniship\Common;

use Omniship\Common\Exception\RuntimeException;

class CarrierFactory
{
    /** @var array<string, class-string<CarrierInterface>> */
    private array $carriers = [];

    /**
     * @return array<string, class-string<CarrierInterface>>
     */
    public function all(): array
    {
        return $this->carriers;
    }

    /**
     * @param array<string, class-string<CarrierInterface>> $carriers
     */
    public function replace(array $carriers): void
    {
        $this->carriers = $carriers;
    }

    /**
     * @param class-string<CarrierInterface> $className
     */
    public function register(string $shortName, string $className): void
    {
        $this->carriers[$shortName] = $className;
    }

    public function create(string $name, mixed ...$args): CarrierInterface
    {
        $className = $this->resolveClassName($name);

        if (!class_exists($className)) {
            throw new RuntimeException("Carrier class $className not found. Did you install the correct package?");
        }

        /** @var CarrierInterface */
        return new $className(...$args);
    }

    /**
     * @return class-string<CarrierInterface>|string
     */
    private function resolveClassName(string $name): string
    {
        if (isset($this->carriers[$name])) {
            return $this->carriers[$name];
        }

        if (class_exists($name)) {
            return $name;
        }

        $parts = explode('_', $name);
        $namespace = implode('\\', $parts);

        return "Omniship\\{$namespace}\\Carrier";
    }
}

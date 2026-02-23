<?php

declare(strict_types=1);

namespace Omniship\Common;

class Helper
{
    public static function camelCase(string $str): string
    {
        return lcfirst(str_replace(' ', '', ucwords(str_replace(['_', '-'], ' ', $str))));
    }

    /**
     * Get the carrier short name from a FQCN.
     *
     * Omniship\UPS\Carrier -> UPS
     * Omniship\DHL\Express\Carrier -> DHL_Express
     */
    public static function getCarrierShortName(string $className): string
    {
        if (preg_match('/^Omniship\\\\(.+)\\\\Carrier$/', $className, $matches)) {
            return str_replace('\\', '_', $matches[1]);
        }

        return $className;
    }

    /**
     * Resolve a carrier short name to FQCN.
     *
     * UPS -> Omniship\UPS\Carrier
     * DHL_Express -> Omniship\DHL\Express\Carrier
     */
    public static function getCarrierClassName(string $shortName): string
    {
        if (class_exists($shortName)) {
            return $shortName;
        }

        $parts = explode('_', $shortName);
        $namespace = implode('\\', $parts);

        return "Omniship\\{$namespace}\\Carrier";
    }
}

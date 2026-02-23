<?php

declare(strict_types=1);

namespace Omniship;

use Omniship\Common\CarrierFactory;
use Omniship\Common\CarrierInterface;

final class Omniship
{
    private static ?CarrierFactory $factory = null;

    public static function getFactory(): CarrierFactory
    {
        if (self::$factory === null) {
            self::$factory = new CarrierFactory();
        }

        return self::$factory;
    }

    public static function setFactory(CarrierFactory $factory): void
    {
        self::$factory = $factory;
    }

    public static function create(string $name, mixed ...$args): CarrierInterface
    {
        return self::getFactory()->create($name, ...$args);
    }
}

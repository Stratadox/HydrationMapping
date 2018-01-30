<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Scalar;

use Stratadox\Hydration\Mapping\Property\FromSingleKey;
use Stratadox\Hydration\MapsProperty;

abstract class Scalar extends FromSingleKey
{
    public static function inProperty(string $name) : self
    {
        return new static($name, $name);
    }

    public static function inPropertyWithDifferentKey(
        string $name, string $key
    ) : self
    {
        return new static($name, $key);
    }
}

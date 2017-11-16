<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Scalar;

use Stratadox\Hydration\Mapping\Property\FromSingleKey;

abstract class Scalar extends FromSingleKey
{
    public static function inProperty(string $name) : Scalar
    {
        return new static($name, $name);
    }
}

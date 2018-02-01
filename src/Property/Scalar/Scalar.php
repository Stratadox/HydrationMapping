<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Scalar;

use Stratadox\Hydration\Mapping\Property\FromSingleKey;

/**
 * Maps the data from a single key to a scalar object property.
 *
 * @package Stratadox\Hydrate
 * @author Stratadox
 */
abstract class Scalar extends FromSingleKey
{
    /**
     * Create a new mapping for the called-upon scalar type object property.
     *
     * @param string $name The name of both the key and the property.
     * @return static|self The concrete scalar mapping object.
     */
    public static function inProperty(string $name) : self
    {
        return new static($name, $name);
    }

    /**
     * Create a new mapping for the called-upon scalar type object property,
     * using the data from a specific key.
     *
     * @param string $name The name of the property.
     * @param string $key  The array key to use.
     * @return static|self
     */
    public static function inPropertyWithDifferentKey(
        string $name, string $key
    ) : self
    {
        return new static($name, $key);
    }
}

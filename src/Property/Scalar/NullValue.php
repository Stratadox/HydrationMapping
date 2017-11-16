<?php

namespace Stratadox\Hydration\Mapping\Property\Scalar;

/**
 * Maps whatever you give it to null in an object property.
 *
 * @package Stratadox\Hydrate
 * @author Stratadox
 */
class NullValue extends Scalar
{
    public function value(array $data, $owner = null)
    {
        return null;
    }
}

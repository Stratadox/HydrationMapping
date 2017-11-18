<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Scalar;

use function is_bool;
use function is_numeric;
use Stratadox\Hydration\Mapping\Property\UnmappableProperty;

/**
 * Maps boolean-like input to a boolean property in an object property.
 *
 * @package Stratadox\Hydrate
 * @author Stratadox
 */
class BooleanValue extends Scalar
{
    public function value(array $data, $owner = null) : bool
    {
        $value = $this->my($data);
        if (is_bool($value)) {
            return $value;
        }
        if (is_numeric($value)) {
            return $value > 0;
        }
        throw UnmappableProperty::itMustBeConvertibleToBoolean($this, $value);
    }
}

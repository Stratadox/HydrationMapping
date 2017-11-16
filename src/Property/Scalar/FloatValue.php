<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Scalar;

use function is_numeric;
use Stratadox\Hydration\Mapping\Property\UnmappableProperty;

/**
 * Maps numeric input to a float property in an object property.
 *
 * @package Stratadox\Hydrate
 * @author Stratadox
 */
class FloatValue extends Scalar
{
    public function value(array $data, $owner = null) : float
    {
        $value = $this->my($data);
        if (!is_numeric($value)) {
            throw UnmappableProperty::itMustBeNumeric($this, $value);
        }
        return (float) $value;
    }
}

<?php

namespace Stratadox\Hydration\Mapping\Property\Scalar;

use function is_numeric;
use Stratadox\Hydration\Mapping\Property\UnmappableProperty;

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

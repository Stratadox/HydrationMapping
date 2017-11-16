<?php

namespace Stratadox\Hydration\Mapping\Property\Scalar;

use function in_array;
use Stratadox\Hydration\Mapping\Property\UnmappableProperty;

class BooleanValue extends Scalar
{
    private $truths = ['true', 'yes', 'y',];
    private $falsehoods = ['false', 'no', 'n'];

    public function value(array $data, $owner = null) : bool
    {
        $value = $this->my($data);
        if (is_bool($value)) {
            return $value;
        }
        if ($this->isConsideredTrue($value)) {
            return true;
        }
        if ($this->isConsideredFalse($value)) {
            return false;
        }
        throw UnmappableProperty::itMustBeConvertibleToBoolean($this, $value);
    }

    private function isConsideredTrue($value) : bool
    {
        if (is_numeric($value)) {
            return $value > 0;
        }
        return in_array(strtolower($value), $this->truths);
    }

    private function isConsideredFalse($value) : bool
    {
        if (is_numeric($value)) {
            return $value <= 0;
        }
        return in_array(strtolower($value), $this->falsehoods);
    }
}

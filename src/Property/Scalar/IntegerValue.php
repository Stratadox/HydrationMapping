<?php

namespace Stratadox\Hydration\Mapping\Property\Scalar;

use function preg_match;
use Stratadox\Hydration\Mapping\Property\UnmappableProperty;

class IntegerValue extends Scalar
{
    public function value(array $data, $owner = null) : int
    {
        $value = $this->my($data);
        if (!preg_match('/^[-+]?\d+$/', (string) $value)) {
            throw UnmappableProperty::itMustBeLikeAnInteger($this, $value);
        }
        if ($value > PHP_INT_MAX || $value < PHP_INT_MIN) {
            throw UnmappableProperty::itMustInIntegerRange($this, $value);
        }
        return (int) $value;
    }
}
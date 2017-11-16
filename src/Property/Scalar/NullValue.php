<?php

namespace Stratadox\Hydration\Mapping\Property\Scalar;

class NullValue extends Scalar
{
    public function value(array $data, $owner = null)
    {
        return null;
    }
}

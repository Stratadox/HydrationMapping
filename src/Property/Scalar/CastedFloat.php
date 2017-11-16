<?php

namespace Stratadox\Hydration\Mapping\Property\Scalar;

class CastedFloat extends Scalar
{
    public function value(array $data, $owner = null) : float
    {
        return (float) $this->my($data);
    }
}

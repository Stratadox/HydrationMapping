<?php

namespace Stratadox\Hydration\Mapping\Property\Scalar;

class CastedInteger extends Scalar
{
    public function value(array $data, $owner = null) : int
    {
        return (int) $this->my($data);
    }
}

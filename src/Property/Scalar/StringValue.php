<?php

namespace Stratadox\Hydration\Mapping\Property\Scalar;

class StringValue extends Scalar
{
    public function value(array $data, $owner = null) : string
    {
        return (string) $this->my($data);
    }
}

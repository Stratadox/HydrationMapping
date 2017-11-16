<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Scalar;

/**
 * Maps scalar input to a float property in an object property.
 *
 * @package Stratadox\Hydrate
 * @author Stratadox
 */
class CastedFloat extends Scalar
{
    public function value(array $data, $owner = null) : float
    {
        return (float) $this->my($data);
    }
}

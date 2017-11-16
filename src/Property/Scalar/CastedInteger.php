<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Scalar;

/**
 * Maps scalar input to an integer property in an object property.
 *
 * @package Stratadox\Hydrate
 * @author Stratadox
 */
class CastedInteger extends Scalar
{
    public function value(array $data, $owner = null) : int
    {
        return (int) $this->my($data);
    }
}

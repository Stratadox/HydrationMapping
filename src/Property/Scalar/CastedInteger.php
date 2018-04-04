<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Scalar;

/**
 * Maps scalar input to an integer property in an object property.
 *
 * @package Stratadox\Hydrate
 * @author Stratadox
 */
final class CastedInteger extends Scalar
{
    /** @inheritdoc */
    public function value(array $data, $owner = null): int
    {
        return (int) $this->my($data);
    }
}

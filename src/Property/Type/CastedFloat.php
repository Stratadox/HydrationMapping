<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Type;

/**
 * Maps scalar input to a float property in an object property.
 *
 * @author Stratadox
 */
final class CastedFloat extends ScalarValue
{
    /** @inheritdoc */
    public function value(array $data, $owner = null): float
    {
        return (float) $this->my($data);
    }
}

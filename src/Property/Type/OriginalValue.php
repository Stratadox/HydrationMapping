<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Type;

/**
 * Maps the value directly onto an object property.
 *
 * @author Stratadox
 */
final class OriginalValue extends ScalarValue
{
    /** @inheritdoc */
    public function value(array $data, $owner = null)
    {
        return $this->my($data);
    }
}

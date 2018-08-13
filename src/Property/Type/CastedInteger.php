<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Type;

/**
 * Maps scalar input to an integer property in an object property.
 *
 * @package Stratadox\Hydrate
 * @author  Stratadox
 */
final class CastedInteger extends ScalarValue
{
    /** @inheritdoc */
    public function value(array $data, $owner = null): int
    {
        return (int) $this->my($data);
    }
}

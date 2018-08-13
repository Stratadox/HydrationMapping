<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Type;

/**
 * Maps whatever you give it to null in an object property.
 *
 * @package Stratadox\Hydrate
 * @author  Stratadox
 */
final class NullValue extends ScalarValue
{
    /** @inheritdoc */
    public function value(array $data, $owner = null)
    {
        return null;
    }
}

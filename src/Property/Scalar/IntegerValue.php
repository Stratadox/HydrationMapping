<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Scalar;

use function preg_match;
use Stratadox\Hydration\Mapping\Property\UnmappableProperty;

/**
 * Maps integer-like input to an integer property in an object property.
 *
 * @package Stratadox\Hydrate
 * @author  Stratadox
 */
final class IntegerValue extends Scalar
{
    /** @inheritdoc */
    public function value(array $data, $owner = null): int
    {
        $value = $this->my($data);
        if (!preg_match('/^[-+]?\d+$/', (string) $value)) {
            throw UnmappableProperty::itMustBeLikeAnInteger($this, $value);
        }
        if ($value > PHP_INT_MAX || $value < PHP_INT_MIN) {
            throw UnmappableProperty::itMustBeInIntegerRange($this, $value);
        }
        return (int) $value;
    }
}

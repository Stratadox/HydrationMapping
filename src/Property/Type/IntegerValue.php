<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Type;

use function preg_match;
use Stratadox\Hydration\Mapping\Property\UnmappableProperty;

/**
 * Maps integer-like input to an integer property in an object property.
 *
 * @package Stratadox\Hydrate
 * @author  Stratadox
 */
final class IntegerValue extends ScalarValue
{
    /** @inheritdoc */
    public function value(array $data, $owner = null): int
    {
        $value = $this->my($data);
        if (!preg_match('/^[-+]?\d+$/', (string) $value)) {
            throw UnmappableProperty::itMustBeLikeAnInteger($this, $value);
        }
        if ($value > (string) PHP_INT_MAX || $value < (string) PHP_INT_MIN) {
            throw UnmappableProperty::itMustBeInIntegerRange($this, $value);
        }
        return (int) $value;
    }
}

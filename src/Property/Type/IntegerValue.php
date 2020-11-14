<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Type;

use Stratadox\Hydration\Mapping\Property\UnmappableProperty;
use function preg_match;

/**
 * Maps integer-like input to an integer property in an object property.
 *
 * @author Stratadox
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

<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Type;

use Stratadox\Hydration\Mapping\Property\UnmappableProperty;
use Stratadox\HydrationMapping\KeyedMapping;
use function assert;
use function in_array;

/**
 * Maps boolean-like input to a boolean property in an object property.
 *
 * @author Stratadox
 */
final class BooleanValue extends ScalarValue
{
    /** @var mixed[] */
    private $truths = [true, 1, '1'];
    /** @var mixed[] */
    private $falsehoods = [false, 0, '0'];

    /**
     * Creates a new mapping for the boolean type object property.
     *
     * @param string $name       The name of both the key and the property.
     * @param array  $truths     The values that should be considered true.
     * @param array  $falsehoods The values that should be considered false.
     * @return KeyedMapping      The boolean mapping object.
     */
    public static function withCustomTruths(
        string $name,
        array $truths,
        array $falsehoods
    ): KeyedMapping {
        $instance = parent::inProperty($name);

        assert($instance instanceof BooleanValue);
        $instance->truths = $truths;
        $instance->falsehoods = $falsehoods;

        return $instance;
    }

    /**
     * Creates a new mapping for the boolean type object property, using the
     * data from a specific key.
     *
     * @param string $name       The name of the property.
     * @param string $key        The array key to use.
     * @param array  $truths     The values that should be considered true.
     * @param array  $falsehoods The values that should be considered false.
     * @return KeyedMapping      The boolean mapping object.
     */
    public static function withCustomTruthsAndKey(
        string $name,
        string $key,
        array $truths,
        array $falsehoods
    ): KeyedMapping {
        $instance = parent::inPropertyWithDifferentKey($name, $key);

        assert($instance instanceof BooleanValue);
        $instance->truths = $truths;
        $instance->falsehoods = $falsehoods;

        return $instance;
    }

    /** @inheritdoc */
    public function value(array $data, $owner = null): bool
    {
        $value = $this->my($data);
        if (in_array($value, $this->truths, true)) {
            return true;
        }
        if (in_array($value, $this->falsehoods, true)) {
            return false;
        }
        throw UnmappableProperty::itMustBeConvertibleToBoolean($this, $value);
    }
}

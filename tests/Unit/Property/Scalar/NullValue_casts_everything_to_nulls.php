<?php

declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Unit\Property\Scalar;

use PHPUnit\Framework\TestCase;
use Stratadox\Hydration\Mapping\Property\Scalar\NullValue;

/**
 * @covers \Stratadox\Hydration\Mapping\Property\Scalar\NullValue
 * @covers \Stratadox\Hydration\Mapping\Property\Scalar\Scalar
 * @covers \Stratadox\Hydration\Mapping\Property\FromSingleKey
 */
class NullValue_casts_everything_to_nulls extends TestCase
{
    /** @scenario */
    function integer_values_convert_to_null()
    {
        $source = ['null' => 123];

        $map = NullValue::inProperty('null');

        $this->assertNull($map->value($source));
    }

    /** @scenario */
    function boolean_true_converts_to_null()
    {
        $source = ['null' => true];

        $map = NullValue::inProperty('null');

        $this->assertNull($map->value($source));
    }

    /** @scenario */
    function boolean_false_converts_to_null()
    {
        $source = ['null' => false];

        $map = NullValue::inProperty('null');

        $this->assertNull($map->value($source));
    }

    /** @scenario */
    function float_values_convert_to_null()
    {
        $source = ['null' => 10.0002597343609724924];

        $map = NullValue::inProperty('null');

        $this->assertNull($map->value($source));
    }

    /** @scenario */
    function string_values_convert_to_null()
    {
        $source = ['null' => 'Oh no! I will be nullified!'];

        $map = NullValue::inProperty('null');

        $this->assertNull($map->value($source));
    }
}

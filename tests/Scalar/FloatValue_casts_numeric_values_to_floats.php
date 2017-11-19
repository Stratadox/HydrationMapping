<?php

declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test;

use PHPUnit\Framework\TestCase;
use Stratadox\Hydration\Mapping\Property\Scalar\FloatValue;
use Stratadox\Hydration\UnmappableInput;

/**
 * @covers \Stratadox\Hydration\Mapping\Property\Scalar\FloatValue
 * @covers \Stratadox\Hydration\Mapping\Property\UnmappableProperty
 * @covers \Stratadox\Hydration\Mapping\Property\Scalar\Scalar
 * @covers \Stratadox\Hydration\Mapping\Property\FromSingleKey
 */
class FloatValue_casts_numeric_values_to_floats extends TestCase
{
    /** @scenario */
    function float_like_strings_become_floats()
    {
        $source = ['float' => '5.2'];

        $map = FloatValue::inProperty('float');

        $this->assertSame(5.2, $map->value($source));
    }

    /** @scenario */
    function integer_like_string_input_becomes_a_float()
    {
        $source = ['float' => '6'];

        $map = FloatValue::inProperty('float');

        $this->assertSame(6.0, $map->value($source));
    }

    /** @scenario */
    function integers_become_floats()
    {
        $source = ['float' => 3];

        $map = FloatValue::inProperty('float');

        $this->assertSame(3.0, $map->value($source));
    }

    /** @scenario */
    function non_numeric_input_throws_an_exception()
    {
        $source = ['float' => 'NaN'];

        $map = FloatValue::inProperty('float');

        $this->expectException(UnmappableInput::class);
        $map->value($source);
    }

    /** @scenario */
    function null_input_throws_an_exception()
    {
        $source = ['float' => null];

        $map = FloatValue::inProperty('float');

        $this->expectException(UnmappableInput::class);
        $map->value($source);
    }
}

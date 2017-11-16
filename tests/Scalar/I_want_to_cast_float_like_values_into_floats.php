<?php

namespace Stratadox\Hydration\Test\Unit\Mapping;

use PHPUnit\Framework\TestCase;
use Stratadox\Hydration\Mapping\Property\Scalar\FloatValue;
use Stratadox\Hydration\UnmappableInput;

class I_want_to_cast_float_like_values_into_floats extends TestCase
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

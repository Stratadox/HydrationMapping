<?php

namespace Stratadox\Hydration\Test\Unit\Mapping;

use PHPUnit\Framework\TestCase;
use Stratadox\Hydration\Mapping\Property\Scalar\CastedFloat;

class I_want_to_cast_whatever_input_I_get_into_floats extends TestCase
{
    /** @scenario */
    function float_like_strings_become_floats()
    {
        $source = ['float' => '5.2'];

        $map = CastedFloat::inProperty('float');

        $this->assertSame(5.2, $map->value($source));
    }

    /** @scenario */
    function integer_like_string_input_becomes_a_float()
    {
        $source = ['float' => '6'];

        $map = CastedFloat::inProperty('float');

        $this->assertSame(6.0, $map->value($source));
    }

    /** @scenario */
    function integers_become_floats()
    {
        $source = ['float' => 3];

        $map = CastedFloat::inProperty('float');

        $this->assertSame(3.0, $map->value($source));
    }

    /** @scenario */
    function non_numeric_input_becomes_zero()
    {
        $source = ['float' => 'NaN'];

        $map = CastedFloat::inProperty('float');

        $this->assertSame(0.0, $map->value($source));
    }
}

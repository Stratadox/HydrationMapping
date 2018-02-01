<?php

declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Unit\Property\Scalar;

use PHPUnit\Framework\TestCase;
use Stratadox\Hydration\Mapping\Property\Scalar\IntegerValue;
use Stratadox\HydrationMapping\UnmappableInput;

/**
 * @covers \Stratadox\Hydration\Mapping\Property\Scalar\IntegerValue
 * @covers \Stratadox\Hydration\Mapping\Property\UnmappableProperty
 * @covers \Stratadox\Hydration\Mapping\Property\Scalar\Scalar
 * @covers \Stratadox\Hydration\Mapping\Property\FromSingleKey
 */
class IntegerValue_casts_integer_like_values_to_integers extends TestCase
{
    /** @scenario */
    function integer_like_numeric_string_input_become_integer_values()
    {
        $source = ['int' => '123'];

        $map = IntegerValue::inProperty('int');

        $this->assertSame(123, $map->value($source));
    }

    /** @scenario */
    function negative_integer_like_numeric_string_input_become_integer_values()
    {
        $source = ['int' => '-123'];

        $map = IntegerValue::inProperty('int');

        $this->assertSame(-123, $map->value($source));
    }

    /** @scenario */
    function number_over_maximum_integer_limit_throws_an_exception()
    {
        $source = ['int' => '99999999999999999999999999999999999999999999999999'];

        $map = IntegerValue::inProperty('int');

        $this->expectException(UnmappableInput::class);
        $map->value($source);
    }

    /** @scenario */
    function number_under_minimum_integer_limit_throws_an_exception()
    {
        $source = ['int' => '-99999999999999999999999999999999999999999999999999'];

        $map = IntegerValue::inProperty('int');

        $this->expectException(UnmappableInput::class);
        $map->value($source);
    }

    /** @scenario */
    function float_like_input_throws_an_exception()
    {
        $source = ['int' => '6.35'];

        $map = IntegerValue::inProperty('int');

        $this->expectException(UnmappableInput::class);
        $map->value($source);
    }

    /** @scenario */
    function non_numeric_input_throws_an_exception()
    {
        $source = ['int' => 'NaN'];

        $map = IntegerValue::inProperty('int');

        $this->expectException(UnmappableInput::class);
        $map->value($source);
    }

    /** @scenario */
    function null_input_throws_an_exception()
    {
        $source = ['int' => null];

        $map = IntegerValue::inProperty('int');

        $this->expectException(UnmappableInput::class);
        $map->value($source);
    }
}

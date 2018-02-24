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
 */
class IntegerValue_casts_integer_like_values_to_integers extends TestCase
{
    /** @test */
    function integer_like_numeric_string_input_become_integer_values()
    {
        $source = ['int' => '123'];

        $map = IntegerValue::inProperty('int');

        $this->assertSame(123, $map->value($source));
    }

    /** @test */
    function negative_integer_like_numeric_string_input_become_integer_values()
    {
        $source = ['int' => '-123'];

        $map = IntegerValue::inProperty('int');

        $this->assertSame(-123, $map->value($source));
    }

    /** @test */
    function very_large_integer_input_become_integer_values()
    {
        $source = ['int' => PHP_INT_MAX];

        $map = IntegerValue::inProperty('int');

        $this->assertSame(PHP_INT_MAX, $map->value($source));
    }

    /** @test */
    function very_small_integer_input_become_integer_values()
    {
        $source = ['int' => PHP_INT_MIN];

        $map = IntegerValue::inProperty('int');

        $this->assertSame(PHP_INT_MIN, $map->value($source));
    }

    /** @test */
    function number_over_maximum_integer_limit_throws_an_exception()
    {
        $source = ['int' => '99999999999999999999999999999999999999999999999999'];

        $map = IntegerValue::inProperty('int');

        $this->expectException(UnmappableInput::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage(
            'Cannot assign `99999999999999999999999999999999999999999999999999` ' .
            'to property `int`: it is not clean for conversion to integer. The ' .
            'value is out of range.'
        );
        $map->value($source);
    }

    /** @test */
    function number_under_minimum_integer_limit_throws_an_exception()
    {
        $source = ['int' => '-99999999999999999999999999999999999999999999999999'];

        $map = IntegerValue::inProperty('int');

        $this->expectException(UnmappableInput::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage(
            'Cannot assign `-99999999999999999999999999999999999999999999999999` ' .
            'to property `int`: it is not clean for conversion to integer. The ' .
            'value is out of range.'
        );
        $map->value($source);
    }

    /** @test */
    function float_like_input_throws_an_exception()
    {
        $source = ['int' => '6.35'];

        $map = IntegerValue::inProperty('int');

        $this->expectException(UnmappableInput::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage(
            'Cannot assign `6.35` to property `int`: it is not clean for ' .
            'conversion to integer.'
        );
        $map->value($source);
    }

    /** @test */
    function non_numeric_input_throws_an_exception()
    {
        $source = ['int' => 'NaN'];

        $map = IntegerValue::inProperty('int');

        $this->expectException(UnmappableInput::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage(
            'Cannot assign `NaN` to property `int`: it is not clean for ' .
            'conversion to integer.'
        );
        $map->value($source);
    }

    /** @test */
    function null_input_throws_an_exception()
    {
        $source = ['int' => null];

        $map = IntegerValue::inProperty('int');

        $this->expectException(UnmappableInput::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage(
            'Cannot assign the NULL to property `int`: it is not clean for ' .
            'conversion to integer.'
        );
        $map->value($source);
    }
}

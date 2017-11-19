<?php

declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test;

use PHPUnit\Framework\TestCase;
use Stratadox\Hydration\Mapping\Property\Scalar\CastedInteger;

/**
 * @covers \Stratadox\Hydration\Mapping\Property\Scalar\CastedInteger
 * @covers \Stratadox\Hydration\Mapping\Property\Scalar\Scalar
 * @covers \Stratadox\Hydration\Mapping\Property\FromSingleKey
 */
class CastedInteger_casts_the_values_to_integers extends TestCase
{
    /** @scenario */
    function integer_like_numeric_string_input_become_integer_values()
    {
        $source = ['int' => '123'];

        $map = CastedInteger::inProperty('int');

        $this->assertSame(123, $map->value($source));
    }

    /** @scenario */
    function numbers_out_of_range_become_the_highest_possible_integer_value()
    {
        $source = ['int' => '99999999999999999999999999999999999999999999999999'];

        $map = CastedInteger::inProperty('int');

        $this->assertSame(PHP_INT_MAX, $map->value($source));
    }

    /** @scenario */
    function float_like_values_are_rounded_down()
    {
        $source = ['int' => '6.95'];

        $map = CastedInteger::inProperty('int');

        $this->assertSame(6, $map->value($source));
    }

    /** @scenario */
    function non_numeric_values_become_zero()
    {
        $source = ['int' => 'NaN'];

        $map = CastedInteger::inProperty('int');

        $this->assertSame(0, $map->value($source));
    }

    /** @scenario */
    function null_values_become_zero()
    {
        $source = ['int' => null];

        $map = CastedInteger::inProperty('int');

        $this->assertSame(0, $map->value($source));
    }
}
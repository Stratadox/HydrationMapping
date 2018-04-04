<?php
declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Unit\Property\Scalar;

use PHPUnit\Framework\TestCase;
use Stratadox\Hydration\Mapping\Property\Scalar\CastedFloat;

/**
 * @covers \Stratadox\Hydration\Mapping\Property\Scalar\CastedFloat
 * @covers \Stratadox\Hydration\Mapping\Property\Scalar\Scalar
 */
class CastedFloat_casts_the_values_to_floats extends TestCase
{
    /** @test */
    function float_like_strings_become_floats()
    {
        $source = ['float' => '5.2'];

        $map = CastedFloat::inProperty('float');

        $this->assertSame(5.2, $map->value($source));
    }

    /** @test */
    function integer_like_string_input_becomes_a_float()
    {
        $source = ['float' => '6'];

        $map = CastedFloat::inProperty('float');

        $this->assertSame(6.0, $map->value($source));
    }

    /** @test */
    function integers_become_floats()
    {
        $source = ['float' => 3];

        $map = CastedFloat::inProperty('float');

        $this->assertSame(3.0, $map->value($source));
    }

    /** @test */
    function non_numeric_input_becomes_zero()
    {
        $source = ['float' => 'NaN'];

        $map = CastedFloat::inProperty('float');

        $this->assertSame(0.0, $map->value($source));
    }
}

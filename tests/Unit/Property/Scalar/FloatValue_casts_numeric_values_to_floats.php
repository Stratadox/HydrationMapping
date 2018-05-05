<?php
declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Unit\Property\Scalar;

use PHPUnit\Framework\TestCase;
use Stratadox\Hydration\Mapping\Property\Scalar\FloatValue;
use Stratadox\HydrationMapping\UnmappableInput;

/**
 * @covers \Stratadox\Hydration\Mapping\Property\Scalar\FloatValue
 * @covers \Stratadox\Hydration\Mapping\Property\UnmappableProperty
 * @covers \Stratadox\Hydration\Mapping\Property\Scalar\Scalar
 */
class FloatValue_casts_numeric_values_to_floats extends TestCase
{
    /** @test */
    function float_like_strings_become_floats()
    {
        $source = ['float' => '5.2'];

        $map = FloatValue::inProperty('float');

        $this->assertSame(5.2, $map->value($source));
    }

    /** @test */
    function integer_like_string_input_becomes_a_float()
    {
        $source = ['float' => '6'];

        $map = FloatValue::inProperty('float');

        $this->assertSame(6.0, $map->value($source));
    }

    /** @test */
    function integers_become_floats()
    {
        $source = ['float' => 3];

        $map = FloatValue::inProperty('float');

        $this->assertSame(3.0, $map->value($source));
    }

    /** @test */
    function non_numeric_input_throws_an_exception()
    {
        $source = ['float' => 'NaN'];

        $map = FloatValue::inProperty('float');

        $this->expectException(UnmappableInput::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage(
            'Cannot assign `NaN` to property `float`: ' .
            'it is not clean for conversion to float.'
        );
        $map->value($source);
    }

    /** @test */
    function null_input_throws_an_exception()
    {
        $source = ['float' => null];

        $map = FloatValue::inProperty('float');

        $this->expectException(UnmappableInput::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage(
            'Cannot assign the NULL to property `float`: ' .
            'it is not clean for conversion to float.'
        );
        $map->value($source);
    }
}

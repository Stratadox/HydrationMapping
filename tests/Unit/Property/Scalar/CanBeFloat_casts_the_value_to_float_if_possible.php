<?php
declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Unit\Property\Scalar;

use PHPUnit\Framework\TestCase;
use Stratadox\Hydration\Mapping\Property\Scalar\BooleanValue;
use Stratadox\Hydration\Mapping\Property\Scalar\CanBeFloat;
use Stratadox\Hydration\Mapping\Property\Scalar\StringValue;
use Stratadox\HydrationMapping\UnmappableInput;

/**
 * @covers \Stratadox\Hydration\Mapping\Property\Scalar\CanBeFloat
 * @covers \Stratadox\Hydration\Mapping\Property\MissingTheKey
 * @covers \Stratadox\Hydration\Mapping\Property\UnmappableProperty
 */
class CanBeFloat_casts_the_value_to_float_if_possible extends TestCase
{
    /** @test */
    function mapping_numeric_values_to_floats()
    {
        $source = ['mixed' => '16'];

        $map = CanBeFloat::or(StringValue::inProperty('mixed'));

        $this->assertSame(16.0, $map->value($source));
    }

    /** @test */
    function falling_back_to_the_alternative_if_the_input_is_not_numeric()
    {
        $source = ['mixed' => 'NaN'];

        $map = CanBeFloat::or(StringValue::inProperty('mixed'));

        $this->assertSame('NaN', $map->value($source));
    }

    /** @test */
    function retrieving_which_property_to_map_to()
    {
        $map = CanBeFloat::or(StringValue::inProperty('mixed'));

        $this->assertSame('mixed', $map->name());
    }

    /** @test */
    function retrieving_the_key_to_use_for_the_input_data()
    {
        $map = CanBeFloat::or(
            StringValue::inPropertyWithDifferentKey('mixed', 'key')
        );

        $this->assertSame('key', $map->key());
    }

    /** @test */
    function missing_input_throws_an_exception()
    {
        $map = CanBeFloat::or(StringValue::inProperty('mixed'));

        $this->expectException(UnmappableInput::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage(
            'Missing the key `mixed` for property `mixed` the ' .
            'input data: []; Mapper: ' . CanBeFloat::class
        );

        $map->value([]);
    }

    /** @test */
    function adding_to_the_exception_message_of_the_wrapped_mapping()
    {
        $source = ['mixed' => 'NaN'];

        $map = CanBeFloat::or(BooleanValue::inProperty('mixed'));

        $this->expectException(UnmappableInput::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage(
            'Cannot assign `NaN` to property `mixed`: it is not clean for ' .
            'conversion to boolean. It could not be mapped to float either.'
        );

        $map->value($source);
    }
}

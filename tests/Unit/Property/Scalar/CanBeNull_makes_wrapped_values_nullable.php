<?php
declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Unit\Property\Scalar;

use PHPUnit\Framework\TestCase;
use Stratadox\Hydration\Mapping\Property\MissingTheKey;
use Stratadox\Hydration\Mapping\Property\Scalar\BooleanValue;
use Stratadox\Hydration\Mapping\Property\Scalar\CanBeNull;
use Stratadox\Hydration\Mapping\Property\Scalar\FloatValue;
use Stratadox\Hydration\Mapping\Property\Scalar\IntegerValue;
use Stratadox\Hydration\Mapping\Property\Scalar\StringValue;
use Stratadox\HydrationMapping\UnmappableInput;

/**
 * @covers \Stratadox\Hydration\Mapping\Property\Scalar\CanBeNull
 * @covers \Stratadox\Hydration\Mapping\Property\UnmappableProperty
 */
class CanBeNull_makes_wrapped_values_nullable extends TestCase
{
    /** @test */
    function allowing_null_input_when_wrapping_a_string_mapping()
    {
        $source = ['string' => null];

        $map = CanBeNull::or(StringValue::inProperty('string'));

        $this->assertNull($map->value($source));
    }

    /** @test */
    function allowing_null_input_when_wrapping_an_integer_mapping()
    {
        $source = ['int' => null];

        $map = CanBeNull::or(IntegerValue::inProperty('int'));

        $this->assertNull($map->value($source));
    }

    /** @test */
    function allowing_integer_like_input_when_wrapping_an_integer_mapping()
    {
        $source = ['int' => '123'];

        $map = CanBeNull::or(IntegerValue::inProperty('int'));

        $this->assertSame(123, $map->value($source));
    }

    /** @test */
    function number_over_maximum_integer_limit_throws_an_exception_when_wrapping_an_integer_mapping()
    {
        $source = ['int' => '99999999999999999999999999999999999999999999999999'];

        $map = CanBeNull::or(IntegerValue::inProperty('int'));

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
    function non_numeric_input_throws_an_exception_when_wrapping_a_float_mapping()
    {
        $source = ['float' => 'NaN'];

        $map = CanBeNull::or(FloatValue::inProperty('float'));

        $this->expectException(UnmappableInput::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage(
            'Cannot assign `NaN` to property `float`: it is not clean for ' .
            'conversion to float.'
        );
        $map->value($source);
    }

    /** @test */
    function nullable_type_mapping_knows_which_property_to_map_to()
    {
        $map = CanBeNull::or(BooleanValue::inProperty('boolean'));
        $this->assertSame('boolean', $map->name());
    }

    /** @test */
    function nullable_type_mapping_can_itself_also_be_wrapped()
    {
        $map = CanBeNull::or(
            BooleanValue::inPropertyWithDifferentKey('bool', 'key')
        );
        $this->assertSame('key', $map->key());
    }

    /** @test */
    function throwing_an_exception_when_the_data_is_missing()
    {
        $map = CanBeNull::or(BooleanValue::inProperty('boolean'));

        $this->expectException(MissingTheKey::class);

        $map->value([]);
    }
}

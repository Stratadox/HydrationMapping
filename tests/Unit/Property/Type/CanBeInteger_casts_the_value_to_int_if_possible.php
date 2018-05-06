<?php
declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Unit\Property\Type;

use function bcadd;
use function bcsub;
use const PHP_INT_MAX;
use PHPUnit\Framework\TestCase;
use Stratadox\Hydration\Mapping\Property\Type\CanBeInteger;
use Stratadox\Hydration\Mapping\Property\Type\FloatValue;
use Stratadox\HydrationMapping\UnmappableInput;

/**
 * @covers \Stratadox\Hydration\Mapping\Property\Type\CanBeInteger
 * @covers \Stratadox\Hydration\Mapping\Property\MissingTheKey
 * @covers \Stratadox\Hydration\Mapping\Property\UnmappableProperty
 */
class CanBeInteger_casts_the_value_to_int_if_possible extends TestCase
{
    /** @test */
    function mapping_integer_like_values_to_integers()
    {
        $source = ['number' => '16'];

        $map = CanBeInteger::or(FloatValue::inProperty('number'));

        $this->assertSame(16, $map->value($source));
    }

    /** @test */
    function keeping_integers_as_they_are()
    {
        $source = ['number' => PHP_INT_MAX];

        $map = CanBeInteger::or(FloatValue::inProperty('number'));

        $this->assertInternalType('integer', $map->value($source));
    }

    /** @test */
    function mapping_large_integer_like_values_to_integers()
    {
        $source = ['number' => (string) PHP_INT_MAX];

        $map = CanBeInteger::or(FloatValue::inProperty('number'));

        $this->assertInternalType('integer', $map->value($source));
    }

    /** @test */
    function mapping_very_small_integer_like_values_to_integers()
    {
        $source = ['number' => (string) PHP_INT_MIN];

        $map = CanBeInteger::or(FloatValue::inProperty('number'));

        $this->assertInternalType('integer', $map->value($source));
    }

    /** @test */
    function falling_back_to_the_alternative_if_the_input_is_not_an_integer()
    {
        $source = ['number' => '1.6'];

        $map = CanBeInteger::or(FloatValue::inProperty('number'));

        $this->assertSame(1.6, $map->value($source));
    }

    /** @test */
    function falling_back_to_the_alternative_if_the_input_is_too_large_for_an_integer()
    {
        $source = ['number' => bcadd((string) PHP_INT_MAX, '1')];

        $map = CanBeInteger::or(FloatValue::inProperty('number'));

        $this->assertInternalType('float', $map->value($source));
    }

    /** @test */
    function falling_back_to_the_alternative_if_the_input_is_too_small_for_an_integer()
    {
        $source = ['number' => bcsub((string) PHP_INT_MIN, '1')];

        $map = CanBeInteger::or(FloatValue::inProperty('number'));

        $this->assertInternalType('float', $map->value($source));
    }

    /** @test */
    function retrieving_which_property_to_map_to()
    {
        $map = CanBeInteger::or(FloatValue::inProperty('number'));

        $this->assertSame('number', $map->name());
    }

    /** @test */
    function retrieving_the_key_to_use_for_the_input_data()
    {
        $map = CanBeInteger::or(
            FloatValue::inPropertyWithDifferentKey('number', 'key')
        );

        $this->assertSame('key', $map->key());
    }

    /** @test */
    function missing_input_throws_an_exception()
    {
        $map = CanBeInteger::or(FloatValue::inProperty('number'));

        $this->expectException(UnmappableInput::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage(
            'Missing the key `number` for property `number` in the ' .
            'input data: []; Mapper: ' . CanBeInteger::class
        );

        $map->value([]);
    }

    /** @test */
    function adding_to_the_exception_message_of_the_wrapped_mapping()
    {
        $source = ['number' => 'NaN'];

        $map = CanBeInteger::or(FloatValue::inProperty('number'));

        $this->expectException(UnmappableInput::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage(
            'Cannot assign `NaN` to property `number`: it is not clean for ' .
            'conversion to float. It could not be mapped to integer either.'
        );

        $map->value($source);
    }
}

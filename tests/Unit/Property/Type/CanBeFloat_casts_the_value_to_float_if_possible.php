<?php
declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Unit\Property\Type;

use PHPUnit\Framework\TestCase;
use Stratadox\Hydration\Mapping\Property\MissingTheKey;
use Stratadox\Hydration\Mapping\Property\Type\BooleanValue;
use Stratadox\Hydration\Mapping\Property\Type\CanBeFloat;
use Stratadox\Hydration\Mapping\Property\Type\StringValue;
use Stratadox\HydrationMapping\MappingFailure;

class CanBeFloat_casts_the_value_to_float_if_possible extends TestCase
{
    /** @test */
    function mapping_numeric_values_to_floats()
    {
        $source = ['mixed' => '16'];

        $map = CanBeFloat::or(StringValue::inProperty('mixed'));

        self::assertSame(16.0, $map->value($source));
    }

    /** @test */
    function falling_back_to_the_alternative_if_the_input_is_not_numeric()
    {
        $source = ['mixed' => 'NaN'];

        $map = CanBeFloat::or(StringValue::inProperty('mixed'));

        self::assertSame('NaN', $map->value($source));
    }

    /** @test */
    function retrieving_which_property_to_map_to()
    {
        $map = CanBeFloat::or(StringValue::inProperty('mixed'));

        self::assertSame('mixed', $map->name());
    }

    /** @test */
    function retrieving_the_key_to_use_for_the_input_data()
    {
        $map = CanBeFloat::or(
            StringValue::inPropertyWithDifferentKey('mixed', 'key')
        );

        self::assertSame('key', $map->key());
    }

    /** @test */
    function missing_input_throws_an_exception()
    {
        $map = CanBeFloat::or(StringValue::inProperty('mixed'));

        $this->expectException(MissingTheKey::class);

        $map->value([]);
    }

    /** @test */
    function adding_to_the_exception_message_of_the_wrapped_mapping()
    {
        $source = ['mixed' => 'NaN'];

        $map = CanBeFloat::or(BooleanValue::inProperty('mixed'));

        $this->expectException(MappingFailure::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage(
            'Cannot assign `NaN` to property `mixed`: it is not clean for ' .
            'conversion to boolean. It could not be mapped to float either.'
        );

        $map->value($source);
    }
}

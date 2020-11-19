<?php declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test;

use PHPUnit\Framework\TestCase;
use stdClass;
use Stratadox\Deserializer\DeserializationFailure;
use Stratadox\Deserializer\ObjectDeserializer;
use Stratadox\Hydration\Mapping\Property\Type\BooleanValue;
use Stratadox\Hydration\Mapping\Property\Type\CastedFloat;
use Stratadox\Hydration\Mapping\Property\Type\CastedInteger;
use Stratadox\Hydration\Mapping\Property\Type\FloatValue;
use Stratadox\Hydration\Mapping\Property\Type\IntegerValue;
use Stratadox\Hydration\Mapping\Property\Type\NullValue;
use Stratadox\Hydration\Mapping\Property\Type\OriginalValue;
use Stratadox\Hydration\Mapping\Property\Type\StringValue;
use Stratadox\HydrationMapping\Test\Double\Foo\Foo;
use Stratadox\Hydrator\MappedHydrator;
use Stratadox\Hydrator\ObjectHydrator;
use Stratadox\Instantiator\ObjectInstantiator;

/**
 * @testdox Mapping basic data types
 */
class Mapping_basic_data_types extends TestCase
{
    /** @test */
    function mapping_all_the_types_from_zeroes()
    {
        $deserialize = ObjectDeserializer::using(
            ObjectInstantiator::forThe(Foo::class),
            MappedHydrator::using(
                ObjectHydrator::default(),
                IntegerValue::inProperty('integer'),
                StringValue::inProperty('string'),
                BooleanValue::inProperty('boolean'),
                FloatValue::inProperty('float'),
                NullValue::inProperty('null'),
                OriginalValue::inProperty('mixed')
            )
        );

        /** @var Foo $foo */
        $foo = $deserialize->from([
            'integer' => 0,
            'string' => 0,
            'boolean' => 0,
            'float' => 0,
            'null' => 0,
            'mixed' => 0,
        ]);

        self::assertSame(0, $foo->integer());
        self::assertSame('0', $foo->string());
        self::assertFalse($foo->boolean());
        self::assertSame(0.0, $foo->float());
        self::assertNull($foo->null());
        self::assertSame(0, $foo->mixed());
    }

    /** @test */
    function mapping_all_the_types_from_ones()
    {
        $deserialize = ObjectDeserializer::using(
            ObjectInstantiator::forThe(Foo::class),
            MappedHydrator::using(
                ObjectHydrator::default(),
                IntegerValue::inProperty('integer'),
                StringValue::inProperty('string'),
                BooleanValue::inProperty('boolean'),
                FloatValue::inProperty('float'),
                NullValue::inProperty('null'),
                OriginalValue::inProperty('mixed')
            )
        );

        /** @var Foo $foo */
        $foo = $deserialize->from([
            'integer' => 1,
            'string' => 1,
            'boolean' => 1,
            'float' => 1,
            'null' => 1,
            'mixed' => 1,
        ]);

        self::assertSame(1, $foo->integer());
        self::assertSame('1', $foo->string());
        self::assertTrue($foo->boolean());
        self::assertSame(1.0, $foo->float());
        self::assertNull($foo->null());
        self::assertSame(1, $foo->mixed());
    }

    /** @test */
    function mapping_all_the_types_from_one_zero()
    {
        $deserialize = ObjectDeserializer::using(
            ObjectInstantiator::forThe(Foo::class),
            MappedHydrator::using(
                ObjectHydrator::default(),
                IntegerValue::inPropertyWithDifferentKey('integer', 'foo'),
                StringValue::inPropertyWithDifferentKey('string', 'foo'),
                BooleanValue::inPropertyWithDifferentKey('boolean', 'foo'),
                FloatValue::inPropertyWithDifferentKey('float', 'foo'),
                NullValue::inPropertyWithDifferentKey('null', 'foo')
            )
        );

        /** @var Foo $foo */
        $foo = $deserialize->from(['foo' => 0]);

        self::assertSame(0, $foo->integer());
        self::assertSame('0', $foo->string());
        self::assertFalse($foo->boolean());
        self::assertSame(0.0, $foo->float());
        self::assertNull($foo->null());
    }

    /** @test */
    function mapping_a_boolean_with_custom_true_and_false_values()
    {
        $deserialize = ObjectDeserializer::using(
            ObjectInstantiator::forThe(Foo::class),
            MappedHydrator::using(
                ObjectHydrator::default(),
                BooleanValue::withCustomTruths('boolean', ['Y'], ['N'])
            )
        );

        /** @var Foo $fooTrue */
        $fooTrue = $deserialize->from(['boolean' => 'Y']);
        /** @var Foo $fooFalse */
        $fooFalse = $deserialize->from(['boolean' => 'N']);

        self::assertTrue($fooTrue->boolean());
        self::assertFalse($fooFalse->boolean());
    }

    /** @test */
    function mapping_a_boolean_with_custom_true_and_false_values_and_key()
    {
        $deserialize = ObjectDeserializer::using(
            ObjectInstantiator::forThe(Foo::class),
            MappedHydrator::using(
                ObjectHydrator::default(),
                BooleanValue::withCustomTruthsAndKey('boolean', 'x', ['Y'], ['N'])
            )
        );

        /** @var Foo $fooTrue */
        $fooTrue = $deserialize->from(['x' => 'Y']);
        /** @var Foo $fooFalse */
        $fooFalse = $deserialize->from(['x' => 'N']);

        self::assertTrue($fooTrue->boolean());
        self::assertFalse($fooFalse->boolean());
    }

    /** @test */
    function not_mapping_missing_keys()
    {
        $deserialize = ObjectDeserializer::using(
            ObjectInstantiator::forThe(Foo::class),
            MappedHydrator::using(
                ObjectHydrator::default(),
                IntegerValue::inProperty('integer'),
                StringValue::inProperty('string_missing'),
                BooleanValue::inProperty('boolean'),
                FloatValue::inProperty('float'),
                NullValue::inProperty('null'),
                OriginalValue::inProperty('mixed')
            )
        );

        $this->expectException(DeserializationFailure::class);
        $this->expectExceptionMessage('string_missing');

        $deserialize->from([
            'integer' => 0,
            'boolean' => 0,
            'float' => 0,
            'null' => 0,
            'mixed' => 0,
        ]);
    }

    /** @test */
    function not_mapping_invalid_integer_values()
    {
        $deserialize = ObjectDeserializer::using(
            ObjectInstantiator::forThe(Foo::class),
            MappedHydrator::using(
                ObjectHydrator::default(),
                IntegerValue::inProperty('integer')
            )
        );

        $this->expectException(DeserializationFailure::class);
        $this->expectExceptionMessage('NaN');

        $deserialize->from(['integer' => 'NaN']);
    }

    /** @test */
    function mapping_casted_integer_values()
    {
        $deserialize = ObjectDeserializer::using(
            ObjectInstantiator::forThe(Foo::class),
            MappedHydrator::using(
                ObjectHydrator::default(),
                CastedInteger::inProperty('integer')
            )
        );

        /** @var Foo $foo */
        $foo = $deserialize->from(['integer' => 'NaN']);

        self::assertSame(0, $foo->integer());
    }

    /** @test */
    function mapping_casted_float_values()
    {
        $deserialize = ObjectDeserializer::using(
            ObjectInstantiator::forThe(Foo::class),
            MappedHydrator::using(
                ObjectHydrator::default(),
                CastedFloat::inProperty('float')
            )
        );

        /** @var Foo $foo */
        $foo = $deserialize->from(['float' => 'NaN']);

        self::assertSame(0.0, $foo->float());
    }

    /** @test */
    function not_mapping_integer_values_outside_of_integer_range()
    {
        $deserialize = ObjectDeserializer::using(
            ObjectInstantiator::forThe(Foo::class),
            MappedHydrator::using(
                ObjectHydrator::default(),
                IntegerValue::inProperty('integer')
            )
        );

        $this->expectException(DeserializationFailure::class);
        $this->expectExceptionMessage('range');

        $deserialize->from(['integer' => '99999999999999999999999999999999999']);
    }

    /** @test */
    function not_mapping_invalid_float_values()
    {
        $deserialize = ObjectDeserializer::using(
            ObjectInstantiator::forThe(Foo::class),
            MappedHydrator::using(
                ObjectHydrator::default(),
                FloatValue::inProperty('float')
            )
        );

        $this->expectException(DeserializationFailure::class);
        $this->expectExceptionMessage('NaN');

        $deserialize->from(['float' => 'NaN']);
    }

    /** @test */
    function not_mapping_objects_as_float_values()
    {
        $deserialize = ObjectDeserializer::using(
            ObjectInstantiator::forThe(Foo::class),
            MappedHydrator::using(
                ObjectHydrator::default(),
                FloatValue::inProperty('float')
            )
        );

        $this->expectException(DeserializationFailure::class);
        $this->expectExceptionMessage('object');

        $deserialize->from(['float' => new stdClass()]);
    }

    /** @test */
    function not_mapping_invalid_boolean_values()
    {
        $deserialize = ObjectDeserializer::using(
            ObjectInstantiator::forThe(Foo::class),
            MappedHydrator::using(
                ObjectHydrator::default(),
                BooleanValue::inProperty('boolean')
            )
        );

        $this->expectException(DeserializationFailure::class);
        $this->expectExceptionMessage('Hello world');

        $deserialize->from(['boolean' => 'Hello world']);
    }
}

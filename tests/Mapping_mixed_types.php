<?php declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test;

use PHPUnit\Framework\TestCase;
use stdClass;
use Stratadox\Deserializer\DeserializationFailure;
use Stratadox\Deserializer\ObjectDeserializer;
use Stratadox\Hydration\Mapping\Property\Type\BooleanValue;
use Stratadox\Hydration\Mapping\Property\Type\CanBeBoolean;
use Stratadox\Hydration\Mapping\Property\Type\CanBeFloat;
use Stratadox\Hydration\Mapping\Property\Type\CanBeInteger;
use Stratadox\Hydration\Mapping\Property\Type\CanBeNull;
use Stratadox\Hydration\Mapping\Property\Type\FloatValue;
use Stratadox\Hydration\Mapping\Property\Type\IntegerValue;
use Stratadox\Hydration\Mapping\Property\Type\StringValue;
use Stratadox\HydrationMapping\Test\Double\Foo\Nullable;
use Stratadox\Hydrator\MappedHydrator;
use Stratadox\Hydrator\ObjectHydrator;
use Stratadox\Hydrator\ReflectiveHydrator;
use Stratadox\Instantiator\ObjectInstantiator;

/**
 * @testdox Mapping mixed types
 */
class Mapping_mixed_types extends TestCase
{
    /** @test */
    function mapping_nullable_types_with_non_null_values()
    {
        $deserialize = ObjectDeserializer::using(
            ObjectInstantiator::forThe(Nullable::class),
            MappedHydrator::using(
                ObjectHydrator::default(),
                CanBeNull::or(IntegerValue::inProperty('integer')),
                CanBeNull::or(StringValue::inProperty('string')),
                CanBeNull::or(BooleanValue::inProperty('boolean')),
                CanBeNull::or(FloatValue::inProperty('float'))
            )
        );

        /** @var Nullable $nullable */
        $nullable = $deserialize->from([
            'integer' => '105',
            'string' => 'hello',
            'boolean' => '1',
            'float' => '102.45',
        ]);

        self::assertSame(105, $nullable->integer());
        self::assertSame('hello', $nullable->string());
        self::assertTrue($nullable->boolean());
        self::assertSame(102.45, $nullable->float());
    }

    /** @test */
    function mapping_nullable_types_with_null_values()
    {
        $deserialize = ObjectDeserializer::using(
            ObjectInstantiator::forThe(Nullable::class),
            MappedHydrator::using(
                ObjectHydrator::default(),
                CanBeNull::or(IntegerValue::inProperty('integer')),
                CanBeNull::or(StringValue::inProperty('string')),
                CanBeNull::or(BooleanValue::inProperty('boolean')),
                CanBeNull::or(FloatValue::inProperty('float'))
            )
        );

        /** @var Nullable $nullable */
        $nullable = $deserialize->from([
            'integer' => null,
            'string' => null,
            'boolean' => null,
            'float' => null,
        ]);

        self::assertNull($nullable->integer());
        self::assertNull($nullable->string());
        self::assertNull($nullable->boolean());
        self::assertNull($nullable->float());
    }

    /** @test */
    function not_mapping_when_the_nullable_integer_is_not_null_nor_integer()
    {
        $deserialize = ObjectDeserializer::using(
            ObjectInstantiator::forThe(Nullable::class),
            MappedHydrator::using(
                ObjectHydrator::default(),
                CanBeNull::or(IntegerValue::inProperty('integer'))
            )
        );

        $this->expectException(DeserializationFailure::class);
        $this->expectExceptionMessage('foo');

        $deserialize->from(['integer' => 'foo']);
    }

    /** @test */
    function mapping_to_integer_or_float()
    {
        $deserialize = ObjectDeserializer::using(
            ObjectInstantiator::forThe(stdClass::class),
            MappedHydrator::using(
                ReflectiveHydrator::default(),
                CanBeInteger::or(FloatValue::inProperty('number'))
            )
        );

        $int = $deserialize->from(['number' => '5']);
        $float = $deserialize->from(['number' => '5.0']);
        $big = $deserialize->from(['number' => '99999999999999999999999999999']);

        self::assertSame(5, $int->number);
        self::assertSame(5.0, $float->number);
        self::assertIsFloat($big->number);
    }

    /** @test */
    function not_mapping_to_integer_or_float_with_non_numeric_input()
    {
        $deserialize = ObjectDeserializer::using(
            ObjectInstantiator::forThe(stdClass::class),
            MappedHydrator::using(
                ReflectiveHydrator::default(),
                CanBeInteger::or(FloatValue::inProperty('number'))
            )
        );

        $this->expectException(DeserializationFailure::class);
        $this->expectExceptionMessage('integer');

        $deserialize->from(['number' => 'Not a number']);
    }

    /** @test */
    function not_mapping_to_integer_or_float_with_missing_input()
    {
        $deserialize = ObjectDeserializer::using(
            ObjectInstantiator::forThe(stdClass::class),
            MappedHydrator::using(
                ReflectiveHydrator::default(),
                CanBeInteger::or(FloatValue::inProperty('number'))
            )
        );

        $this->expectException(DeserializationFailure::class);
        $this->expectExceptionMessage('number');

        $deserialize->from([]);
    }

    /** @test */
    function mapping_to_float_or_boolean()
    {
        $deserialize = ObjectDeserializer::using(
            ObjectInstantiator::forThe(stdClass::class),
            MappedHydrator::using(
                ReflectiveHydrator::default(),
                CanBeFloat::or(BooleanValue::withCustomTruths('x', ['y'], ['n']))
            )
        );

        $float = $deserialize->from(['x' => '1.0']);
        $true = $deserialize->from(['x' => 'y']);

        self::assertSame(1.0, $float->x);
        self::assertTrue($true->x);
    }

    /** @test */
    function not_mapping_to_float_or_boolean_with_invalid_input()
    {
        $deserialize = ObjectDeserializer::using(
            ObjectInstantiator::forThe(stdClass::class),
            MappedHydrator::using(
                ReflectiveHydrator::default(),
                CanBeFloat::or(BooleanValue::withCustomTruths('x', ['y'], ['n']))
            )
        );

        $this->expectException(DeserializationFailure::class);
        $this->expectExceptionMessage('float');

        $deserialize->from(['x' => 'Invalid']);
    }

    /** @test */
    function mapping_to_boolean_or_integer()
    {
        $deserialize = ObjectDeserializer::using(
            ObjectInstantiator::forThe(stdClass::class),
            MappedHydrator::using(
                ReflectiveHydrator::default(),
                CanBeBoolean::or(IntegerValue::inProperty('x'))
            )
        );

        $false = $deserialize->from(['x' => '0']);
        $true = $deserialize->from(['x' => '1']);
        $two = $deserialize->from(['x' => '2']);
        $three = $deserialize->from(['x' => '3']);

        self::assertTrue($true->x);
        self::assertFalse($false->x);
        self::assertSame(2, $two->x);
        self::assertSame(3, $three->x);
    }

    /** @test */
    function mapping_to_boolean_or_integer_with_explicit_true_and_false_values()
    {
        $deserialize = ObjectDeserializer::using(
            ObjectInstantiator::forThe(stdClass::class),
            MappedHydrator::using(
                ReflectiveHydrator::default(),
                CanBeBoolean::orCustom(
                    IntegerValue::inProperty('x'),
                    ['true'],
                    ['false']
                )
            )
        );

        $true = $deserialize->from(['x' => 'true']);
        $false = $deserialize->from(['x' => 'false']);
        $zero = $deserialize->from(['x' => '0']);
        $one = $deserialize->from(['x' => '1']);

        self::assertTrue($true->x);
        self::assertFalse($false->x);
        self::assertSame(0, $zero->x);
        self::assertSame(1, $one->x);
    }

    /** @test */
    function not_mapping_to_boolean_or_integer_with_non_accepted_input()
    {
        $deserialize = ObjectDeserializer::using(
            ObjectInstantiator::forThe(stdClass::class),
            MappedHydrator::using(
                ReflectiveHydrator::default(),
                CanBeBoolean::or(IntegerValue::inProperty('x'))
            )
        );

        $this->expectException(DeserializationFailure::class);

        $deserialize->from(['x' => 'Unexpected input']);
    }

    /** @test */
    function mapping_to_integer_float_or_boolean()
    {
        $deserialize = ObjectDeserializer::using(
            ObjectInstantiator::forThe(stdClass::class),
            MappedHydrator::using(
                ReflectiveHydrator::default(),
                CanBeInteger::or(
                    CanBeFloat::or(
                        BooleanValue::withCustomTruths(
                            'x',
                            ['y'],
                            ['n']
                        )
                    )
                )
            )
        );

        $int = $deserialize->from(['x' => '12']);
        $float = $deserialize->from(['x' => '1.5']);
        $boolean = $deserialize->from(['x' => 'y']);

        self::assertIsInt($int->x);
        self::assertIsFloat($float->x);
        self::assertIsBool($boolean->x);
    }

    /** @test */
    function not_mapping_to_integer_float_or_boolean_with_invalid_value()
    {
        $deserialize = ObjectDeserializer::using(
            ObjectInstantiator::forThe(stdClass::class),
            MappedHydrator::using(
                ReflectiveHydrator::default(),
                CanBeInteger::or(
                    CanBeFloat::or(
                        BooleanValue::withCustomTruths(
                            'x',
                            ['y'],
                            ['n']
                        )
                    )
                )
            )
        );

        $this->expectException(DeserializationFailure::class);
        $this->expectExceptionMessageRegExp(
            '/((bool).*(float).*(integer))|((integer).*(float).*(bool))/'
        );

        $deserialize->from(['x' => 'Hello']);
    }

    /** @test */
    function mapping_to_integer_float_boolean_or_string()
    {
        $deserialize = ObjectDeserializer::using(
            ObjectInstantiator::forThe(stdClass::class),
            MappedHydrator::using(
                ReflectiveHydrator::default(),
                CanBeInteger::or(
                    CanBeFloat::or(
                        CanBeBoolean::orCustom(
                            StringValue::inProperty('x'),
                            ['y'],
                            ['n']
                        )
                    )
                )
            )
        );

        $int = $deserialize->from(['x' => '12']);
        $float = $deserialize->from(['x' => '1.5']);
        $boolean = $deserialize->from(['x' => 'y']);
        $string = $deserialize->from(['x' => 'Y']);

        self::assertIsInt($int->x);
        self::assertIsFloat($float->x);
        self::assertIsBool($boolean->x);
        self::assertIsString($string->x);
    }
}

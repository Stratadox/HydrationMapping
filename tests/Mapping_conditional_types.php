<?php declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test;

use PHPUnit\Framework\TestCase;
use Stratadox\Deserializer\DeserializationFailure;
use Stratadox\Deserializer\ObjectDeserializer;
use Stratadox\Hydration\Mapping\Composite\ConstrainedMapping;
use Stratadox\Hydration\Mapping\Simple\Defaults;
use Stratadox\Hydration\Mapping\Simple\Relationship\HasOneEmbedded;
use Stratadox\Hydration\Mapping\Simple\Type\IntegerValue;
use Stratadox\HydrationMapping\Test\Double\Foo\Foo;
use Stratadox\HydrationMapping\Test\Double\Foo\IsNotMore;
use Stratadox\HydrationMapping\Test\Double\Thing\IsLonger;
use Stratadox\HydrationMapping\Test\Double\Thing\Name;
use Stratadox\HydrationMapping\Test\Double\Thing\Thing;
use Stratadox\Hydrator\MappedHydrator;
use Stratadox\Hydrator\ObjectHydrator;
use Stratadox\Instantiator\ObjectInstantiator;

/**
 * @testdox Mapping conditional types
 */
class Mapping_conditional_types extends TestCase
{
    /** @test */
    function ignoring_mapping_problems_with_a_default_value()
    {
        $deserialize = ObjectDeserializer::using(
            ObjectInstantiator::forThe(Foo::class),
            MappedHydrator::using(
                ObjectHydrator::default(),
                Defaults::to(-1, IntegerValue::inProperty('integer'))
            )
        );

        /** @var Foo $fooNumeric */
        $fooNumeric = $deserialize->from(['integer' => '10']);
        /** @var Foo $fooNan */
        $fooNan = $deserialize->from(['integer' => 'NaN']);

        self::assertSame(10, $fooNumeric->integer());
        self::assertSame(-1, $fooNan->integer());
    }

    /** @test */
    function mapping_an_integer_with_a_constraint()
    {
        $deserialize = ObjectDeserializer::using(
            ObjectInstantiator::forThe(Foo::class),
            MappedHydrator::using(
                ObjectHydrator::default(),
                ConstrainedMapping::checkThatIt(
                    IsNotMore::than(10),
                    IntegerValue::inProperty('integer')
                )
            )
        );

        /** @var Foo $foo */
        $foo = $deserialize->from(['integer' => '10']);

        self::assertSame(10, $foo->integer());
    }

    /** @test */
    function not_mapping_an_integer_with_an_unmet_constraint()
    {
        $deserialize = ObjectDeserializer::using(
            ObjectInstantiator::forThe(Foo::class),
            MappedHydrator::using(
                ObjectHydrator::default(),
                ConstrainedMapping::checkThatIt(
                    IsNotMore::than(10),
                    IntegerValue::inProperty('integer')
                )
            )
        );

        $this->expectException(DeserializationFailure::class);
        $this->expectExceptionMessage('11');

        $deserialize->from(['integer' => '11']);
    }

    /** @test */
    function mapping_an_object_with_a_constraint()
    {
        $deserialize = ObjectDeserializer::using(
            ObjectInstantiator::forThe(Thing::class),
            MappedHydrator::using(
                ObjectHydrator::default(),
                ConstrainedMapping::checkThatIt(
                    IsLonger::than(2),
                    HasOneEmbedded::inProperty(
                        'name',
                        ObjectDeserializer::forThe(Name::class)
                    )
                )
            )
        );

        /** @var Thing $thing */
        $thing = $deserialize->from(['name' => 'Foo']);

        self::assertEquals(new Name('Foo'), $thing->name());
    }

    /** @test */
    function not_mapping_an_object_with_an_unmet_constraint()
    {
        $deserialize = ObjectDeserializer::using(
            ObjectInstantiator::forThe(Thing::class),
            MappedHydrator::using(
                ObjectHydrator::default(),
                ConstrainedMapping::checkThatIt(
                    IsLonger::than(2),
                    HasOneEmbedded::inProperty(
                        'name',
                        ObjectDeserializer::forThe(Name::class)
                    )
                )
            )
        );

        $this->expectException(DeserializationFailure::class);
        $this->expectExceptionMessage(Name::class);

        $deserialize->from(['name' => 'F']);
    }
}

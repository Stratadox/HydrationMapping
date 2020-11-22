<?php declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test;

use PHPUnit\Framework\TestCase;
use Stratadox\Deserializer\CollectionDeserializer;
use Stratadox\Deserializer\DeserializationFailure;
use Stratadox\Deserializer\ObjectDeserializer;
use Stratadox\Hydration\Mapping\Property\Check;
use Stratadox\Hydration\Mapping\Property\Relationship\HasManyNested;
use Stratadox\Hydration\Mapping\Property\Relationship\HasOneEmbedded;
use Stratadox\Hydration\Mapping\Property\Relationship\HasOneNested;
use Stratadox\Hydration\Mapping\Property\Type\BooleanValue;
use Stratadox\Hydration\Mapping\Property\Type\FloatValue;
use Stratadox\Hydration\Mapping\Property\Type\IntegerValue;
use Stratadox\Hydration\Mapping\Property\Type\NullValue;
use Stratadox\Hydration\Mapping\Property\Type\StringValue;
use Stratadox\HydrationMapping\Test\Double\Film\Film;
use Stratadox\HydrationMapping\Test\Double\Film\FilmCollection;
use Stratadox\HydrationMapping\Test\Double\Film\FilmCollector;
use Stratadox\HydrationMapping\Test\Double\Foo\Bar;
use Stratadox\HydrationMapping\Test\Double\Foo\Foo;
use Stratadox\HydrationMapping\Test\Double\Foo\IsNotMore;
use Stratadox\Hydrator\MappedHydrator;
use Stratadox\Hydrator\ObjectHydrator;
use Stratadox\Instantiator\ObjectInstantiator;

/**
 * @testdox Mapping object relationships
 */
class Mapping_object_relationships extends TestCase
{
    /** @test */
    function deserializing_an_object_with_an_embedded_relationship()
    {
        $deserialize = ObjectDeserializer::using(
            ObjectInstantiator::forThe(Bar::class),
            MappedHydrator::using(
                ObjectHydrator::default(),
                HasOneEmbedded::inProperty('foo', ObjectDeserializer::using(
                    ObjectInstantiator::forThe(Foo::class),
                    MappedHydrator::using(
                        ObjectHydrator::default(),
                        IntegerValue::inPropertyWithDifferentKey('integer', 'foo'),
                        StringValue::inPropertyWithDifferentKey('string', 'foo'),
                        BooleanValue::inPropertyWithDifferentKey('boolean', 'foo'),
                        FloatValue::inPropertyWithDifferentKey('float', 'foo'),
                        NullValue::inPropertyWithDifferentKey('null', 'foo')
                    )
                ))
            )
        );

        /** @var Bar $bar */
        $bar = $deserialize->from(['foo' => 1]);

        self::assertSame(1, $bar->integer());
    }

    /** @test */
    function not_deserializing_an_object_with_invalid_embedded_data()
    {
        $deserialize = ObjectDeserializer::using(
            ObjectInstantiator::forThe(Bar::class),
            MappedHydrator::using(
                ObjectHydrator::default(),
                HasOneEmbedded::inProperty('foo', ObjectDeserializer::using(
                    ObjectInstantiator::forThe(Foo::class),
                    MappedHydrator::using(
                        ObjectHydrator::default(),
                        IntegerValue::inPropertyWithDifferentKey('integer', 'foo'),
                        StringValue::inPropertyWithDifferentKey('string', 'foo'),
                        BooleanValue::inPropertyWithDifferentKey('boolean', 'foo'),
                        FloatValue::inPropertyWithDifferentKey('float', 'foo'),
                        NullValue::inPropertyWithDifferentKey('null', 'foo')
                    )
                ))
            )
        );

        $this->expectException(DeserializationFailure::class);
        $this->expectExceptionMessage('10');

        $deserialize->from(['foo' => 10]);
    }

    /** @test */
    function deserializing_an_object_with_a_nested_relationship()
    {
        $deserialize = ObjectDeserializer::using(
            ObjectInstantiator::forThe(Bar::class),
            MappedHydrator::using(
                ObjectHydrator::default(),
                HasOneNested::inProperty('foo', ObjectDeserializer::using(
                    ObjectInstantiator::forThe(Foo::class),
                    MappedHydrator::using(
                        ObjectHydrator::default(),
                        IntegerValue::inPropertyWithDifferentKey('integer', 'foo'),
                        StringValue::inPropertyWithDifferentKey('string', 'foo'),
                        BooleanValue::inPropertyWithDifferentKey('boolean', 'foo'),
                        FloatValue::inPropertyWithDifferentKey('float', 'foo'),
                        NullValue::inPropertyWithDifferentKey('null', 'foo')
                    )
                ))
            )
        );

        /** @var Bar $bar */
        $bar = $deserialize->from(['foo' => ['foo' => 1]]);

        self::assertSame(1, $bar->integer());
    }

    /** @test */
    function deserializing_an_object_with_a_nested_relationship_with_differing_key()
    {
        $deserialize = ObjectDeserializer::using(
            ObjectInstantiator::forThe(Bar::class),
            MappedHydrator::using(
                ObjectHydrator::default(),
                HasOneNested::inPropertyWithDifferentKey(
                    'foo',
                    'bar',
                    ObjectDeserializer::using(
                        ObjectInstantiator::forThe(Foo::class),
                        MappedHydrator::using(
                            ObjectHydrator::default(),
                            IntegerValue::inPropertyWithDifferentKey('integer', 'foo'),
                            StringValue::inPropertyWithDifferentKey('string', 'foo'),
                            BooleanValue::inPropertyWithDifferentKey('boolean', 'foo'),
                            FloatValue::inPropertyWithDifferentKey('float', 'foo'),
                            NullValue::inPropertyWithDifferentKey('null', 'foo')
                        )
                    )
                )
            )
        );

        /** @var Bar $bar */
        $bar = $deserialize->from(['bar' => ['foo' => 1]]);

        self::assertSame(1, $bar->integer());
    }

    /** @test */
    function not_deserializing_an_object_with_invalid_nested_data()
    {
        $deserialize = ObjectDeserializer::using(
            ObjectInstantiator::forThe(Bar::class),
            MappedHydrator::using(
                ObjectHydrator::default(),
                HasOneNested::inProperty('foo', ObjectDeserializer::using(
                    ObjectInstantiator::forThe(Foo::class),
                    MappedHydrator::using(
                        ObjectHydrator::default(),
                        IntegerValue::inPropertyWithDifferentKey('integer', 'foo'),
                        StringValue::inPropertyWithDifferentKey('string', 'foo'),
                        BooleanValue::inPropertyWithDifferentKey('boolean', 'foo'),
                        FloatValue::inPropertyWithDifferentKey('float', 'foo'),
                        NullValue::inPropertyWithDifferentKey('null', 'foo')
                    )
                ))
            )
        );

        $this->expectException(DeserializationFailure::class);
        $this->expectExceptionMessage('10');

        $deserialize->from(['foo' => ['foo' => 10]]);
    }

    /** @test */
    function deserializing_a_nested_collection()
    {
        $deserialize = ObjectDeserializer::using(
            ObjectInstantiator::forThe(FilmCollector::class),
            MappedHydrator::using(
                ObjectHydrator::default(),
                StringValue::inProperty('name'),
                HasManyNested::inPropertyWithDifferentKey(
                    'filmCollection',
                    'films',
                    CollectionDeserializer::forImmutable(FilmCollection::class),
                    ObjectDeserializer::using(
                        ObjectInstantiator::forThe(Film::class),
                        MappedHydrator::using(
                            ObjectHydrator::default(),
                            StringValue::inProperty('name'),
                            StringValue::inProperty('thumbnail'),
                            IntegerValue::inProperty('rating')
                        )
                    )
                )
            )
        );

        /** @var FilmCollector $collector */
        $collector = $deserialize->from([
            'name' => 'Batman',
            'films' => [
                [
                    'name' => 'Foo',
                    'thumbnail' => 'foo_small.jpg',
                    'rating' => 5,
                ],
                [
                    'name' => 'Foo II',
                    'thumbnail' => 'foo2_small.jpg',
                    'rating' => 3,
                ],
                [
                    'name' => 'Bar',
                    'thumbnail' => 'bar_thumb.jpg',
                    'rating' => 4,
                ],
            ]
        ]);

        self::assertSame('Batman', $collector->name());
        self::assertCount(3, $collector->filmCollection());
        self::assertSame('Foo', $collector->filmCollection()[0]->name());
        self::assertSame('Foo II', $collector->filmCollection()[1]->name());
        self::assertSame('Bar', $collector->filmCollection()[2]->name());
        self::assertSame('foo_small.jpg', $collector->filmCollection()[0]->thumbnail());
        self::assertSame(5, $collector->filmCollection()[0]->rating());
    }

    /** @test */
    function not_mapping_related_objects_that_are_not_accepted_by_the_collection()
    {

        $deserialize = ObjectDeserializer::using(
            ObjectInstantiator::forThe(FilmCollector::class),
            MappedHydrator::using(
                ObjectHydrator::default(),
                StringValue::inProperty('name'),
                HasManyNested::inPropertyWithDifferentKey(
                    'filmCollection',
                    'films',
                    CollectionDeserializer::forImmutable(FilmCollection::class),
                    ObjectDeserializer::forThe(Foo::class)
                )
            )
        );

        $this->expectException(DeserializationFailure::class);
        $this->expectExceptionMessage('filmCollection');

        $deserialize->from([
            'name' => 'Batman',
            'films' => [
                [
                    'name' => 'Foo',
                    'thumbnail' => 'foo_small.jpg',
                    'rating' => 5,
                ],
                [
                    'name' => 'Foo II',
                    'thumbnail' => 'foo2_small.jpg',
                    'rating' => 3,
                ],
                [
                    'name' => 'Bar',
                    'thumbnail' => 'bar_thumb.jpg',
                    'rating' => 4,
                ],
            ]
        ]);
    }

    /** @test */
    function not_mapping_related_objects_if_those_objects_cannot_be_mapped()
    {

        $deserialize = ObjectDeserializer::using(
            ObjectInstantiator::forThe(FilmCollector::class),
            MappedHydrator::using(
                ObjectHydrator::default(),
                StringValue::inProperty('name'),
                HasManyNested::inPropertyWithDifferentKey(
                    'filmCollection',
                    'films',
                    CollectionDeserializer::forImmutable(FilmCollection::class),
                    ObjectDeserializer::using(
                        ObjectInstantiator::forThe(Film::class),
                        MappedHydrator::using(
                            ObjectHydrator::default(),
                            StringValue::inProperty('name'),
                            StringValue::inProperty('thumbnail'),
                            Check::thatIt(IsNotMore::than(5), IntegerValue::inProperty('rating'))
                        )
                    )
                )
            )
        );

        $this->expectException(DeserializationFailure::class);
        $this->expectExceptionMessage('filmCollection');

        $deserialize->from([
            'name' => 'Batman',
            'films' => [
                [
                    'name' => 'Foo',
                    'thumbnail' => 'foo_small.jpg',
                    'rating' => 5,
                ],
                [
                    'name' => 'Foo II',
                    'thumbnail' => 'foo2_small.jpg',
                    'rating' => 3,
                ],
                [
                    'name' => 'Bar',
                    'thumbnail' => 'bar_thumb.jpg',
                    'rating' => 14,
                ],
            ]
        ]);
    }

    /** @test */
    function not_mapping_related_objects_if_the_data_is_missing()
    {
        $deserialize = ObjectDeserializer::using(
            ObjectInstantiator::forThe(FilmCollector::class),
            MappedHydrator::using(
                ObjectHydrator::default(),
                StringValue::inProperty('name'),
                HasManyNested::inPropertyWithDifferentKey(
                    'filmCollection',
                    'films',
                    CollectionDeserializer::forImmutable(FilmCollection::class),
                    ObjectDeserializer::forThe(Film::class)
                )
            )
        );

        $this->expectException(DeserializationFailure::class);
        $this->expectExceptionMessage('films');

        $deserialize->from(['name' => 'Batman']);
    }
}

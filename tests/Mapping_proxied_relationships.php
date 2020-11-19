<?php declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test;

use PHPUnit\Framework\TestCase;
use Stratadox\Deserializer\CollectionDeserializer;
use Stratadox\Deserializer\DeserializationFailure;
use Stratadox\Deserializer\ObjectDeserializer;
use Stratadox\Hydration\Mapping\Property\Relationship\HasManyProxies;
use Stratadox\Hydration\Mapping\Property\Relationship\HasOneProxy;
use Stratadox\Hydration\Mapping\Property\Type\IntegerValue;
use Stratadox\Hydration\Mapping\Property\Type\StringValue;
use Stratadox\HydrationMapping\Test\Double\Film\Film;
use Stratadox\HydrationMapping\Test\Double\Film\FilmCollection;
use Stratadox\HydrationMapping\Test\Double\Film\FilmCollector;
use Stratadox\HydrationMapping\Test\Double\Film\FilmLoader;
use Stratadox\HydrationMapping\Test\Double\Film\FilmProxy;
use Stratadox\HydrationMapping\Test\Double\InvalidProxyFactory;
use Stratadox\HydrationMapping\Test\Double\Thing\NameLoader;
use Stratadox\HydrationMapping\Test\Double\Thing\NameProxy;
use Stratadox\HydrationMapping\Test\Double\Thing\Thing;
use Stratadox\Hydrator\MappedHydrator;
use Stratadox\Hydrator\ObjectHydrator;
use Stratadox\Instantiator\ObjectInstantiator;
use Stratadox\Proxy\BasicProxyFactory;

/**
 * @testdox Mapping proxied relationships
 */
class Mapping_proxied_relationships extends TestCase
{
    /** @test */
    function mapping_an_object_with_one_lazily_loaded_related_object()
    {
        $deserialize = ObjectDeserializer::using(
            ObjectInstantiator::forThe(Thing::class),
            MappedHydrator::using(
                ObjectHydrator::default(),
                HasOneProxy::inProperty('name', BasicProxyFactory::for(
                    NameProxy::class,
                    $loader = NameLoader::loadingAs('Lazy')
                ))
            )
        );

        /** @var Thing $thing */
        $thing = $deserialize->from([]);

        self::assertFalse($loader->didLoad());
        self::assertEquals('Lazy', $thing->name());
        self::assertTrue($loader->didLoad());
    }

    /** @test */
    function not_mapping_a_lazy_proxy_with_a_dysfunctional_proxy_loader()
    {
        $deserialize = ObjectDeserializer::using(
            ObjectInstantiator::forThe(Thing::class),
            MappedHydrator::using(
                ObjectHydrator::default(),
                HasOneProxy::inProperty('name', InvalidProxyFactory::make())
            )
        );

        $this->expectException(DeserializationFailure::class);
        $this->expectExceptionMessage('proxy');

        $deserialize->from([]);
    }

    /** @test */
    function mapping_an_object_with_a_list_of_lazily_loaded_related_objects()
    {
        $deserialize = ObjectDeserializer::using(
            ObjectInstantiator::forThe(FilmCollector::class),
            MappedHydrator::using(
                ObjectHydrator::default(),
                StringValue::inProperty('name'),
                HasManyProxies::inPropertyWithDifferentKey(
                    'filmCollection',
                    'films_count',
                    CollectionDeserializer::forImmutable(FilmCollection::class),
                    BasicProxyFactory::for(
                        FilmProxy::class,
                        $loader = new FilmLoader(
                            ObjectDeserializer::using(
                                ObjectInstantiator::forThe(Film::class),
                                MappedHydrator::using(
                                    ObjectHydrator::default(),
                                    StringValue::inProperty('name'),
                                    StringValue::inProperty('thumbnail'),
                                    IntegerValue::inProperty('rating')
                                )
                            ),
                            // Note: a real proxy loader would likely fetch this
                            // data from some kind of database instead
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
                            ]
                        )
                    )
                )
            )
        );

        /** @var FilmCollector $collector */
        $collector = $deserialize->from([
            'name' => 'John Doe',
            'films_count' => 3
        ]);

        self::assertEquals('Bar', $collector->filmCollection()[2]->name());
        self::assertEquals(1, $loader->loaded());

        self::assertEquals('Foo II', $collector->filmCollection()[1]->name());
        self::assertEquals(2, $loader->loaded());

        self::assertEquals('Foo', $collector->filmCollection()[0]->name());
        self::assertEquals(3, $loader->loaded());
    }

    /** @test */
    function not_mapping_lazy_proxies_with_a_dysfunctional_proxy_loader()
    {
        $deserialize = ObjectDeserializer::using(
            ObjectInstantiator::forThe(FilmCollector::class),
            MappedHydrator::using(
                ObjectHydrator::default(),
                StringValue::inProperty('name'),
                HasManyProxies::inProperty(
                    'filmCollection',
                    CollectionDeserializer::forImmutable(FilmCollection::class),
                    InvalidProxyFactory::make()
                )
            )
        );

        $this->expectException(DeserializationFailure::class);
        $this->expectExceptionMessage('proxy');

        $deserialize->from([
            'name' => 'Foo',
            'filmCollection' => 1,
        ]);
    }

    /** @test */
    function not_mapping_a_strictly_typed_collection_with_the_wrong_proxies()
    {
        $deserialize = ObjectDeserializer::using(
            ObjectInstantiator::forThe(FilmCollector::class),
            MappedHydrator::using(
                ObjectHydrator::default(),
                StringValue::inProperty('name'),
                HasManyProxies::inProperty(
                    'filmCollection',
                    CollectionDeserializer::forImmutable(FilmCollection::class),
                    BasicProxyFactory::for(
                        NameProxy::class,
                        NameLoader::loadingAs('foo')
                    )
                )
            )
        );

        $this->expectException(DeserializationFailure::class);
        $this->expectExceptionMessage(NameProxy::class);

        $deserialize->from([
            'name' => 'John Doe',
            'filmCollection' => 3
        ]);
    }
}

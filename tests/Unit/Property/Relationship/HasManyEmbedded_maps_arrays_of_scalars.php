<?php
declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Unit\Property\Relationship;

use PHPUnit\Framework\TestCase;
use Stratadox\Hydration\Mapping\Property\Relationship\HasManyEmbedded;
use Stratadox\HydrationMapping\MappingFailure;
use Stratadox\HydrationMapping\Test\Double\Title\Title;
use Stratadox\HydrationMapping\Test\Double\Title\Titles;
use Stratadox\HydrationMapping\Test\Double\Deserializers;

class HasManyEmbedded_maps_arrays_of_scalars extends TestCase
{
    use Deserializers;

    /** @test */
    function mapping_an_array_of_strings_to_a_collection_of_titles()
    {
        $mapping = HasManyEmbedded::inProperty('name',
            $this->immutableCollectionDeserializerFor(Titles::class),
            $this->deserializerForThe(Title::class),
            'title'
        );

        /** @var Titles $titles */
        $titles = $mapping->value(['foo', 'bar', 'baz']);

        self::assertSame('foo', $titles[0]->title);
        self::assertSame('bar', $titles[1]->title);
        self::assertSame('baz', $titles[2]->title);
    }

    /** @test */
    function using_key_as_default_key()
    {
        $mapping = HasManyEmbedded::inProperty('name',
            $this->immutableCollectionDeserializerFor(Titles::class),
            $this->deserializerForThe(Title::class)
        );

        /** @var Titles $titles */
        $titles = $mapping->value(['foo']);

        self::assertNull($titles[0]->title);
        self::assertSame('foo', $titles[0]->key);
    }

    /** @test */
    function mapping_to_a_property()
    {
        $mapping = HasManyEmbedded::inProperty('foo',
            $this->immutableCollectionDeserializerFor(Titles::class),
            $this->deserializerForThe(Title::class),
            'title'
        );

        self::assertSame(
            'foo',
            $mapping->name()
        );
    }

    /** @test */
    function throwing_an_informative_exception_when_the_items_cannot_be_mapped()
    {
        $mapping = HasManyEmbedded::inProperty('foo',
            $this->immutableCollectionDeserializerFor(Titles::class),
            $this->exceptionThrowingDeserializer('Original message here.')
        );

        $this->expectException(MappingFailure::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage(
            'Failed to map the HasManyEmbedded items of the `foo` property: Original message here.'
        );

        $mapping->value(['bar']);
    }

    /** @test */
    function throwing_an_informative_exception_when_the_collection_cannot_be_mapped()
    {
        $mapping = HasManyEmbedded::inProperty('foo',
            $this->exceptionThrowingCollectionDeserializer('Original message here.'),
            $this->deserializerForThe(Title::class)
        );

        $this->expectException(MappingFailure::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage(
            'Failed to map the HasManyEmbedded collection of the `foo` property: Original message here.'
        );

        $mapping->value(['bar']);
    }
}

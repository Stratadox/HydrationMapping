<?php
declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Unit\Property\Relationship;

use PHPUnit\Framework\TestCase;
use Stratadox\Hydration\Mapping\Property\Relationship\HasManyEmbedded;
use Stratadox\HydrationMapping\UnmappableInput;
use Stratadox\HydrationMapping\Test\Double\Title\Title;
use Stratadox\HydrationMapping\Test\Double\Title\Titles;
use Stratadox\HydrationMapping\Test\Double\MockHydrator;

/**
 * @covers \Stratadox\Hydration\Mapping\Property\Relationship\HasManyEmbedded
 * @covers \Stratadox\Hydration\Mapping\Property\Relationship\CollectionMappingFailed
 */
class HasManyEmbedded_maps_arrays_of_scalars extends TestCase
{
    use MockHydrator;

    /** @test */
    function mapping_an_array_of_strings_to_a_collection_of_titles()
    {
        $mapping = HasManyEmbedded::inProperty('name',
            $this->mockCollectionHydratorForThe(Titles::class),
            $this->mockPublicSetterHydratorForThe(Title::class),
            'title'
        );

        /** @var Titles $titles */
        $titles = $mapping->value(['foo', 'bar', 'baz']);

        $this->assertSame('foo', $titles[0]->title);
        $this->assertSame('bar', $titles[1]->title);
        $this->assertSame('baz', $titles[2]->title);
    }

    /** @test */
    function using_key_as_default_key()
    {
        $mapping = HasManyEmbedded::inProperty('name',
            $this->mockCollectionHydratorForThe(Titles::class),
            $this->mockPublicSetterHydratorForThe(Title::class)
        );

        /** @var Titles $titles */
        $titles = $mapping->value(['foo']);

        $this->assertNull($titles[0]->title);
        $this->assertSame('foo', $titles[0]->key);
    }

    /** @test */
    function mapping_to_a_property()
    {
        $mapping = HasManyEmbedded::inProperty('foo',
            $this->mockCollectionHydratorForThe(Titles::class),
            $this->mockPublicSetterHydratorForThe(Title::class),
            'title'
        );

        $this->assertSame(
            'foo',
            $mapping->name()
        );
    }

    /** @test */
    function throwing_an_informative_exception_when_the_items_cannot_be_mapped()
    {
        $mapping = HasManyEmbedded::inProperty('foo',
            $this->mockCollectionHydratorForThe(Titles::class),
            $this->mockExceptionThrowingHydrator('Original message here.')
        );

        $this->expectException(UnmappableInput::class);
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
            $this->mockExceptionThrowingHydrator('Original message here.'),
            $this->mockPublicSetterHydratorForThe(Title::class)
        );

        $this->expectException(UnmappableInput::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage(
            'Failed to map the HasManyEmbedded collection of the `foo` property: Original message here.'
        );

        $mapping->value(['bar']);
    }
}

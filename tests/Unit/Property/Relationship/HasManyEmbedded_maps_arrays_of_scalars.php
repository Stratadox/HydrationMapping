<?php

declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Unit\Property\Relationship;

use PHPUnit\Framework\TestCase;
use Stratadox\Hydration\Mapping\Property\Relationship\HasManyEmbedded;
use Stratadox\HydrationMapping\Test\Doubles\Title\Title;
use Stratadox\HydrationMapping\Test\Doubles\Title\Titles;
use Stratadox\HydrationMapping\Test\Doubles\MockHydrator;

/**
 * @covers \Stratadox\Hydration\Mapping\Property\Relationship\HasManyEmbedded
 */
class HasManyEmbedded_maps_arrays_of_scalars extends TestCase
{
    use MockHydrator;

    /** @scenario */
    function mapping_an_array_of_strings_to_a_collection_of_titles()
    {
        $mapping = HasManyEmbedded::inProperty('name',
            $this->mockCollectionHydratorForThe(Titles::class),
            $this->mockPublicSetterHydratorForThe(Title::class),
            'title'
        );

        /** @var \Stratadox\HydrationMapping\Test\Doubles\Title\Titles $titles */
        $titles = $mapping->value(['foo', 'bar', 'baz']);

        $this->assertSame('foo', $titles[0]->title);
        $this->assertSame('bar', $titles[1]->title);
        $this->assertSame('baz', $titles[2]->title);
    }

    /** @scenario */
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

    /** @scenario */
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
}

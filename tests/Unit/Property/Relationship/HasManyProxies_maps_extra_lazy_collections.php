<?php

declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Unit\Property\Relationship;

use PHPUnit\Framework\TestCase;
use Stratadox\Hydration\Mapping\Property\Relationship\HasManyProxies;
use Stratadox\HydrationMapping\Test\Double\Person\Person;
use Stratadox\HydrationMapping\Test\Double\Person\PersonProxy;
use Stratadox\HydrationMapping\Test\Double\Person\Persons;
use Stratadox\HydrationMapping\Test\Double\MockHydrator;
use Stratadox\HydrationMapping\Test\Double\MockProxyBuilder;
use Stratadox\HydrationMapping\UnmappableInput;

/**
 * @covers \Stratadox\Hydration\Mapping\Property\Relationship\HasManyProxies
 * @covers \Stratadox\Hydration\Mapping\Property\Relationship\CollectionMappingFailed
 * @covers \Stratadox\Hydration\Mapping\Property\Relationship\ProxyProductionFailed
 */
class HasManyProxies_maps_extra_lazy_collections extends TestCase
{
    use MockHydrator;
    use MockProxyBuilder;

    /** @test */
    function mapping_missing_data_to_a_collection_of_proxies()
    {
        $inSourceData = ['authors' => 3];

        $authorsMapping = HasManyProxies::inProperty('authors',
            $this->mockCollectionHydratorForThe(Persons::class),
            $this->mockProxyBuilderFor(PersonProxy::class)
        );

        /** @var Persons|Person[] $authors */
        $authors = $authorsMapping->value($inSourceData);

        $this->assertCount(3, $authors);
        foreach ($authors as $author) {
            $this->assertSame('Lazy loading', $author->firstName());
            $this->assertSame('Is out of scope', $author->lastName());
        }
    }

    /** @test */
    function proxies_receive_information_on_where_they_are_referenced_from()
    {
        $inSourceData = ['authors' => 3];

        $authorsMapping = HasManyProxies::inProperty('authors',
            $this->mockCollectionHydratorForThe(Persons::class),
            $this->mockProxyBuilderFor(PersonProxy::class)
        );

        /** @var Persons|PersonProxy[] $authors */
        $authors = $authorsMapping->value($inSourceData);

        $this->assertCount(3, $authors);
        foreach ($authors as $i => $author) {
            $this->assertSame('authors', $author->property());
            $this->assertSame($i, $author->position());
        }
    }

    /** @test */
    function proxies_receive_information_on_who_references_them()
    {
        $inSourceData = ['authors' => 2];

        $authorsMapping = HasManyProxies::inProperty('authors',
            $this->mockCollectionHydratorForThe(Persons::class),
            $this->mockProxyBuilderFor(PersonProxy::class)
        );

        /** @var Persons|PersonProxy[] $authors */
        $authors = $authorsMapping->value($inSourceData, $this);

        $this->assertCount(2, $authors);
        foreach ($authors as $i => $author) {
            $this->assertSame($this, $author->owner());
        }
    }

    /** @test */
    function the_source_key_can_differ_from_the_property_name()
    {
        $inSourceData = ['amount' => 3];

        $authorsMapping = HasManyProxies::inPropertyWithDifferentKey('authors', 'amount',
            $this->mockCollectionHydratorForThe(Persons::class),
            $this->mockProxyBuilderFor(PersonProxy::class)
        );

        /** @var Persons|Person[] $authors */
        $authors = $authorsMapping->value($inSourceData);

        $this->assertCount(3, $authors);
        $this->assertSame('authors', $authorsMapping->name());
    }

    /** @test */
    function throwing_an_informative_exception_when_the_source_is_missing()
    {
        $mapping = HasManyProxies::inProperty('foo',
            $this->mockCollectionHydratorForThe(Persons::class),
            $this->mockProxyBuilderFor(PersonProxy::class)
        );

        $this->expectException(UnmappableInput::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage(
            'Missing the key `foo` for property `foo` the input data: []; ' .
            'Mapper: '.HasManyProxies::class
        );

        $mapping->value([]);
    }

    /** @test */
    function throwing_an_informative_exception_when_the_collection_cannot_be_mapped()
    {
        $mapping = HasManyProxies::inProperty('foo',
            $this->mockExceptionThrowingHydrator('Original message here.'),
            $this->mockProxyBuilderFor(PersonProxy::class)
        );

        $this->expectException(UnmappableInput::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage(
            'Failed to map the HasManyProxies collection of the `foo` property: Original message here.'
        );

        $mapping->value(['foo' => 1]);
    }

    /** @test */
    function throwing_an_informative_exception_when_the_proxies_cannot_be_built()
    {
        $mapping = HasManyProxies::inProperty('foo',
            $this->mockCollectionHydratorForThe(Persons::class),
            $this->mockExceptionThrowingProxyBuilder('Original message here.')
        );

        $this->expectException(UnmappableInput::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage(
            'Proxy production for in the `foo` property failed: Original message here.'
        );

        $mapping->value(['foo' => 1]);
    }
}

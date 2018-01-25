<?php

declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Unit\Property\Relationship;

use PHPUnit\Framework\TestCase;
use StdClass;
use Stratadox\Hydration\Mapping\Property\Relationship\HasManyProxies;
use Stratadox\HydrationMapping\Test\Doubles\Author\Author;
use Stratadox\HydrationMapping\Test\Doubles\Author\AuthorProxy;
use Stratadox\HydrationMapping\Test\Doubles\Author\Authors;
use Stratadox\HydrationMapping\Test\Doubles\MockHydrator;
use Stratadox\HydrationMapping\Test\Doubles\MockProxyBuilder;

/**
 * @covers \Stratadox\Hydration\Mapping\Property\Relationship\HasManyProxies
 * @covers \Stratadox\Hydration\Mapping\Property\FromSingleKey
 */
class HasManyProxies_maps_extra_lazy_collections extends TestCase
{
    use MockHydrator;
    use MockProxyBuilder;

    /** @scenario */
    function mapping_missing_data_to_a_collection_of_proxies()
    {
        $inSourceData = ['authors' => 3];

        $authorsMapping = HasManyProxies::inProperty('authors',
            $this->mockCollectionHydratorForThe(Authors::class),
            $this->mockProxyBuilderFor(AuthorProxy::class)
        );

        /** @var Authors|Author[] $authors */
        $authors = $authorsMapping->value($inSourceData);

        $this->assertCount(3, $authors);
        foreach ($authors as $author) {
            $this->assertSame('Lazy loading', $author->firstName());
            $this->assertSame('Is out of scope', $author->lastName());
        }
    }

    /** @scenario */
    function proxies_receive_information_on_where_they_are_referenced_from()
    {
        $inSourceData = ['authors' => 3];

        $authorsMapping = HasManyProxies::inProperty('authors',
            $this->mockCollectionHydratorForThe(Authors::class),
            $this->mockProxyBuilderFor(AuthorProxy::class)
        );

        /** @var Authors|AuthorProxy[] $authors */
        $authors = $authorsMapping->value($inSourceData);

        $this->assertCount(3, $authors);
        foreach ($authors as $i => $author) {
            $this->assertSame('authors', $author->property());
            $this->assertSame($i, $author->position());
        }
    }

    /** @scenario */
    function proxies_receive_information_on_who_references_them()
    {
        $inSourceData = ['authors' => 2];

        $authorsMapping = HasManyProxies::inProperty('authors',
            $this->mockCollectionHydratorForThe(Authors::class),
            $this->mockProxyBuilderFor(AuthorProxy::class)
        );

        /** @var \Stratadox\HydrationMapping\Test\Doubles\Authors\Authors|AuthorProxy[] $authors */
        $authors = $authorsMapping->value($inSourceData, $this);

        $this->assertCount(2, $authors);
        foreach ($authors as $i => $author) {
            $this->assertSame($this, $author->owner());
        }
    }

    /** @scenario */
    function the_source_key_can_differ_from_the_property_name()
    {
        $inSourceData = ['amount' => 3];

        $authorsMapping = HasManyProxies::inPropertyWithDifferentKey('authors', 'amount',
            $this->mockCollectionHydratorForThe(Authors::class),
            $this->mockProxyBuilderFor(AuthorProxy::class)
        );

        /** @var \Stratadox\HydrationMapping\Test\Doubles\Authors\Authors|Author[] $authors */
        $authors = $authorsMapping->value($inSourceData);

        $this->assertCount(3, $authors);
        $this->assertSame('authors', $authorsMapping->name());
    }
}

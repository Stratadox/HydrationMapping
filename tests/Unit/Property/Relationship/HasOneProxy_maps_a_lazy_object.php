<?php

declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Unit\Property\Relationship;

use PHPUnit\Framework\TestCase;
use Stratadox\Hydration\Mapping\Property\Relationship\HasOneProxy;
use Stratadox\Hydration\Proxy;
use Stratadox\HydrationMapping\Test\Double\Author\Author;
use Stratadox\HydrationMapping\Test\Double\Author\AuthorProxy;
use Stratadox\HydrationMapping\Test\Double\MockProxyBuilder;

/**
 * @covers \Stratadox\Hydration\Mapping\Property\Relationship\HasOneProxy
 */
class HasOneProxy_maps_a_lazy_object extends TestCase
{
    use MockProxyBuilder;

    /** @scenario */
    function mapping_an_object_without_any_data()
    {
        $mapping = HasOneProxy::inProperty('author',
            $this->mockProxyBuilderFor(AuthorProxy::class)
        );

        /** @var Author|AuthorProxy $author */
        $author = $mapping->value([]);

        $this->assertInstanceOf(Proxy::class, $author);
        $this->assertSame('Lazy loading', $author->firstName());
        $this->assertSame('Is out of scope', $author->lastName());
    }

    /** @scenario */
    function property_mappers_know_which_property_they_map_to()
    {
        $mapping = HasOneProxy::inProperty('author',
            $this->mockProxyBuilderFor(AuthorProxy::class)
        );

        $this->assertSame('author', $mapping->name());
    }
}

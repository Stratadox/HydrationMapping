<?php

declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test;

use PHPUnit\Framework\TestCase;
use Stratadox\Hydration\Mapping\Property\Relationship\HasOneProxy;
use Stratadox\Hydration\Proxy;
use Stratadox\HydrationMapping\Test\Authors\Author;
use Stratadox\HydrationMapping\Test\Authors\AuthorProxy;
use Stratadox\HydrationMapping\Test\Relationship\MockProxyBuilder;

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
}
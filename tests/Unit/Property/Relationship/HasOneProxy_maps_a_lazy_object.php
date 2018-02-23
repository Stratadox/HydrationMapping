<?php

declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Unit\Property\Relationship;

use PHPUnit\Framework\TestCase;
use Stratadox\Hydration\Mapping\Property\Relationship\HasOneProxy;
use Stratadox\HydrationMapping\Test\Double\Person\Person;
use Stratadox\HydrationMapping\Test\Double\Person\PersonProxy;
use Stratadox\HydrationMapping\Test\Double\MockProxyBuilder;
use Stratadox\HydrationMapping\UnmappableInput;
use Stratadox\Proxy\Proxy;

/**
 * @covers \Stratadox\Hydration\Mapping\Property\Relationship\HasOneProxy
 * @covers \Stratadox\Hydration\Mapping\Property\Relationship\ProxyProductionFailed
 */
class HasOneProxy_maps_a_lazy_object extends TestCase
{
    use MockProxyBuilder;

    /** @test */
    function mapping_an_object_without_any_data()
    {
        $mapping = HasOneProxy::inProperty('author',
            $this->mockProxyBuilderFor(PersonProxy::class)
        );

        /** @var Person|PersonProxy $author */
        $author = $mapping->value([]);

        $this->assertInstanceOf(Proxy::class, $author);
        $this->assertSame('Lazy loading', $author->firstName());
        $this->assertSame('Is out of scope', $author->lastName());
    }

    /** @test */
    function property_mappers_know_which_property_they_map_to()
    {
        $mapping = HasOneProxy::inProperty('author',
            $this->mockProxyBuilderFor(PersonProxy::class)
        );

        $this->assertSame('author', $mapping->name());
    }

    /** @test */
    function throwing_an_informative_exception_when_the_proxy_cannot_be_built()
    {
        $mapping = HasOneProxy::inProperty('foo',
            $this->mockExceptionThrowingProxyBuilder('Original message here.')
        );

        $this->expectException(UnmappableInput::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage(
            'Proxy production for in the `foo` property failed: Original message here.'
        );

        $mapping->value([]);
    }
}

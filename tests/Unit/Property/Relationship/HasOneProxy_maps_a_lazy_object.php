<?php
declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Unit\Property\Relationship;

use PHPUnit\Framework\TestCase;
use stdClass;
use Stratadox\Hydration\Mapping\Property\Relationship\HasOneProxy;
use Stratadox\HydrationMapping\Test\Double\Person\Person;
use Stratadox\HydrationMapping\Test\Double\Person\PersonProxy;
use Stratadox\HydrationMapping\Test\Double\Person\PersonProxyLoader;
use Stratadox\HydrationMapping\Test\Double\ProxyFactories;
use Stratadox\HydrationMapping\MappingFailure;
use Stratadox\HydrationMapping\Test\Double\SpyingLoader;
use Stratadox\Proxy\Proxy;

class HasOneProxy_maps_a_lazy_object extends TestCase
{
    /** @var SpyingLoader */
    private $loader;

    use ProxyFactories;

    protected function setUp(): void
    {
        $this->loader = new SpyingLoader(new PersonProxyLoader());
    }

    /** @test */
    function mapping_an_object_without_any_data()
    {
        $mapping = HasOneProxy::inProperty(
            'author',
            $this->proxyFactoryFor(PersonProxy::class)
        );

        /** @var Person|PersonProxy $author */
        $author = $mapping->value([]);

        self::assertInstanceOf(Proxy::class, $author);
        self::assertSame('Lazy loading', $author->firstName());
        self::assertSame('Is out of scope', $author->lastName());
    }

    /** @test */
    function receiving_information_on_where_the_proxy_is_referenced_from()
    {
        $mapping = HasOneProxy::inProperty(
            'author',
            $this->proxyFactoryFor(PersonProxy::class)
        );

        $owner = new stdClass();

        /** @var Person|PersonProxy $author */
        $author = $mapping->value([], $owner);

        $author->firstName(); // trigger proxy loading
        self::assertSame([
            'owner' => $owner,
            'property' => 'author',
        ], $this->loader->data(0));
    }

    /** @test */
    function property_mappers_know_which_property_they_map_to()
    {
        $mapping = HasOneProxy::inProperty(
            'author',
            $this->proxyFactoryFor(PersonProxy::class)
        );

        self::assertSame('author', $mapping->name());
    }

    /** @test */
    function throwing_an_informative_exception_when_the_proxy_cannot_be_built()
    {
        $mapping = HasOneProxy::inProperty(
            'foo',
            $this->exceptionThrowingProxyFactory('Original message here.')
        );

        $this->expectException(MappingFailure::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage(
            'Proxy production for in the `foo` property failed: Original message here.'
        );

        $mapping->value([]);
    }
}

<?php
declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Unit\Property\Relationship;

use PHPUnit\Framework\TestCase;
use stdClass;
use Stratadox\Hydration\Mapping\Property\MissingTheKey;
use Stratadox\Hydration\Mapping\Property\Relationship\HasManyProxies;
use Stratadox\HydrationMapping\MappingFailure;
use Stratadox\HydrationMapping\Test\Double\Person\Person;
use Stratadox\HydrationMapping\Test\Double\Person\PersonProxy;
use Stratadox\HydrationMapping\Test\Double\Person\PersonProxyLoader;
use Stratadox\HydrationMapping\Test\Double\Person\Persons;
use Stratadox\HydrationMapping\Test\Double\Deserializers;
use Stratadox\HydrationMapping\Test\Double\ProxyFactories;
use Stratadox\HydrationMapping\Test\Double\SpyingLoader;

class HasManyProxies_maps_extra_lazy_collections extends TestCase
{
    /** @var SpyingLoader */
    private $loader;

    use Deserializers;
    use ProxyFactories;

    protected function setUp(): void
    {
        $this->loader = new SpyingLoader(new PersonProxyLoader());
    }

    /** @test */
    function mapping_missing_data_to_a_collection_of_proxies()
    {
        $inSourceData = ['authors' => 3];

        $authorsMapping = HasManyProxies::inProperty(
            'authors',
            $this->immutableCollectionDeserializerFor(Persons::class),
            $this->proxyFactoryFor(PersonProxy::class)
        );

        /** @var Persons|Person[] $authors */
        $authors = $authorsMapping->value($inSourceData);

        self::assertCount(3, $authors);
        foreach ($authors as $author) {
            self::assertSame('Lazy loading', $author->firstName());
            self::assertSame('Is out of scope', $author->lastName());
        }
    }

    /** @test */
    function proxies_receive_information_on_where_they_are_referenced_from()
    {
        $inSourceData = ['authors' => 3];

        $authorsMapping = HasManyProxies::inProperty(
            'authors',
            $this->immutableCollectionDeserializerFor(Persons::class),
            $this->proxyFactoryFor(PersonProxy::class)
        );

        $owner = new stdClass();

        /** @var Persons|PersonProxy[] $authors */
        $authors = $authorsMapping->value($inSourceData, $owner);

        self::assertCount(3, $authors);
        foreach ($authors as $i => $author) {
            $author->firstName(); // trigger proxy loading
            self::assertSame([
                'owner' => $owner,
                'property' => 'authors',
                'offset' => $i,
            ], $this->loader->data($i));
        }
    }

    /** @test */
    function the_source_key_can_differ_from_the_property_name()
    {
        $inSourceData = ['amount' => 3];

        $authorsMapping = HasManyProxies::inPropertyWithDifferentKey(
            'authors',
            'amount',
            $this->immutableCollectionDeserializerFor(Persons::class),
            $this->proxyFactoryFor(PersonProxy::class)
        );

        /** @var Persons|Person[] $authors */
        $authors = $authorsMapping->value($inSourceData);

        self::assertCount(3, $authors);
        self::assertSame('authors', $authorsMapping->name());
    }

    /** @test */
    function throwing_an_exception_when_the_source_is_missing()
    {
        $mapping = HasManyProxies::inProperty(
            'foo',
            $this->immutableCollectionDeserializerFor(Persons::class),
            $this->proxyFactoryFor(PersonProxy::class)
        );

        $this->expectException(MissingTheKey::class);

        $mapping->value([]);
    }

    /** @test */
    function throwing_an_informative_exception_when_the_collection_cannot_be_mapped()
    {
        $mapping = HasManyProxies::inProperty(
            'foo',
            $this->exceptionThrowingCollectionDeserializer('Original message here.'),
            $this->proxyFactoryFor(PersonProxy::class)
        );

        $this->expectException(MappingFailure::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage(
            'Failed to map the HasManyProxies collection of the `foo` property: Original message here.'
        );

        $mapping->value(['foo' => 1]);
    }

    /** @test */
    function throwing_an_informative_exception_when_the_proxies_cannot_be_built()
    {
        $mapping = HasManyProxies::inProperty(
            'foo',
            $this->immutableCollectionDeserializerFor(Persons::class),
            $this->exceptionThrowingProxyFactory('Original message here.')
        );

        $this->expectException(MappingFailure::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage(
            'Proxy production for in the `foo` property failed: Original message here.'
        );

        $mapping->value(['foo' => 1]);
    }
}

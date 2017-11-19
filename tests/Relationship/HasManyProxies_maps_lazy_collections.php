<?php

declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test;

use PHPUnit\Framework\TestCase;
use Stratadox\Hydration\Mapping\Property\Relationship\HasManyProxies;
use Stratadox\Hydration\Test\Authors\Author;
use Stratadox\Hydration\Test\Authors\AuthorProxy;
use Stratadox\Hydration\Test\Authors\Authors;
use Stratadox\Hydration\Test\Relationship\MockHydrator;
use Stratadox\Hydration\Test\Relationship\MockProxyBuilder;

/**
 * @covers \Stratadox\Hydration\Mapping\Property\Relationship\HasManyProxies
 * @covers \Stratadox\Hydration\Mapping\Property\FromSingleKey
 */
class HasManyProxies_maps_lazy_collections extends TestCase
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
}

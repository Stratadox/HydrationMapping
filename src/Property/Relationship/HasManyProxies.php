<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Relationship;

use Stratadox\Deserializer\Deserializer;
use Stratadox\Hydration\Mapping\DifferentKey;
use Stratadox\Hydration\Mapping\Property\Keyed;
use Stratadox\Hydration\Mapping\Relation\NumberedProxyCollectionMapping;
use Stratadox\HydrationMapping\KeyedMapping;
use Stratadox\Proxy\ProxyFactory;

/**
 * Maps a number to a collection of proxies in an object property.
 *
 * @author Stratadox
 */
final class HasManyProxies
{
    public static function inProperty(
        string $name,
        Deserializer $collection,
        ProxyFactory $proxyFactory
    ): KeyedMapping {
        return Keyed::mapping(
            $name,
            NumberedProxyCollectionMapping::inProperty($name, $collection, $proxyFactory)
        );
    }

    public static function inPropertyWithDifferentKey(
        string $name,
        string $key,
        Deserializer $collection,
        ProxyFactory $proxyFactory
    ): KeyedMapping {
        return Keyed::mapping($key, DifferentKey::use(
            $key,
            NumberedProxyCollectionMapping::inProperty($name, $collection, $proxyFactory)
        ));
    }
}

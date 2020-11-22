<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Simple\Relationship;

use Stratadox\Deserializer\Deserializer;
use Stratadox\Hydration\Mapping\DifferentKey;
use Stratadox\Hydration\Mapping\Relation\NumberedProxyCollectionMapping;
use Stratadox\HydrationMapping\Mapping;
use Stratadox\Proxy\ProxyFactory;

final class HasManyProxies
{
    public static function inProperty(
        string $name,
        Deserializer $collection,
        ProxyFactory $proxyFactory
    ): Mapping {
        return NumberedProxyCollectionMapping::inProperty($name, $collection, $proxyFactory);
    }

    public static function inPropertyWithDifferentKey(
        string $name,
        string $key,
        Deserializer $collection,
        ProxyFactory $proxyFactory
    ): Mapping {
        return DifferentKey::use($key, self::inProperty($name, $collection, $proxyFactory));
    }
}

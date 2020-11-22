<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Simple\Relationship;

use Stratadox\Hydration\Mapping\Relation\ProxyMapping;
use Stratadox\HydrationMapping\Mapping;
use Stratadox\Proxy\ProxyFactory;

final class HasOneProxy
{
    public static function inProperty(
        string $name,
        ProxyFactory $proxyFactory
    ): Mapping {
        return ProxyMapping::inProperty($name, $proxyFactory);
    }
}

<?php declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Double;

use Stratadox\Proxy\CompositeProxyFactory;
use Stratadox\Proxy\ProxyFactory;

final class InvalidProxyFactory
{
    public static function make(): ProxyFactory
    {
        return CompositeProxyFactory::using();
    }
}

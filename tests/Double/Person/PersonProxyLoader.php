<?php declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Double\Person;

use Stratadox\Proxy\ProxyLoader;

final class PersonProxyLoader implements ProxyLoader
{
    public function loadTheInstance(array $data): object
    {
        return new Person('Lazy loading', 'Is out of scope');
    }
}

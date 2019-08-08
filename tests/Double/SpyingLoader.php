<?php declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Double;

use Stratadox\Proxy\ProxyLoader;

final class SpyingLoader implements ProxyLoader
{
    private $loader;
    private $receivedData = [];

    public function __construct(ProxyLoader $loader)
    {
        $this->loader = $loader;
    }

    public function loadTheInstance(array $data): object
    {
        $this->receivedData[] = $data;
        return $this->loader->loadTheInstance($data);
    }

    public function data(int $n): array
    {
        return $this->receivedData[$n];
    }
}

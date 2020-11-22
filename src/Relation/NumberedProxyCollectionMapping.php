<?php declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Relation;

use Stratadox\Deserializer\Deserializer;
use Stratadox\Hydration\Mapping\AssertKey;
use Stratadox\HydrationMapping\Mapping;
use Stratadox\Proxy\ProxyFactory;
use Stratadox\Proxy\ProxyProductionFailed;
use Throwable;

final class NumberedProxyCollectionMapping implements Mapping
{
    /** @var string */
    private $name;
    /** @var Deserializer */
    private $collection;
    /** @var ProxyFactory */
    private $proxyFactory;

    private function __construct(
        string $name,
        Deserializer $collection,
        ProxyFactory $proxyFactory
    ) {
        $this->name = $name;
        $this->collection = $collection;
        $this->proxyFactory = $proxyFactory;
    }

    public static function inProperty(
        string $name,
        Deserializer $collection,
        ProxyFactory $proxyFactory
    ): Mapping {
        return new self($name, $collection, $proxyFactory);
    }

    public function name(): string
    {
        return $this->name;
    }

    public function value(array $data, $owner = null)
    {
        AssertKey::exists($this, $data, $this->name());
        $amount = $data[$this->name()];
        try {
            $proxies = $this->makeSomeProxies($amount, $owner);
        } catch (Throwable $exception) {
            throw ProxyMappingFailure::encountered($this, $exception);
        }
        try {
            return $this->collection->from($proxies);
        } catch (Throwable $exception) {
            throw CollectionMappingFailure::encountered($this, $exception);
        }
    }

    /** @throws ProxyProductionFailed */
    private function makeSomeProxies(int $amount, ?object $owner): array
    {
        $proxies = [];
        for ($i = 0; $i < $amount; ++$i) {
            $proxies[] = $this->proxyFactory->create([
                'owner' => $owner,
                'property' => $this->name(),
                'offset' => $i,
            ]);
        }
        return $proxies;
    }
}

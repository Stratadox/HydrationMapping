<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Relationship;

use Stratadox\Deserializer\Deserializer;
use Stratadox\HydrationMapping\KeyedMapping;
use Stratadox\Proxy\ProxyFactory;
use Stratadox\Proxy\ProxyProductionFailed as ProxyException;
use Throwable;

/**
 * Maps a number to a collection of proxies in an object property.
 *
 * @author Stratadox
 */
final class HasManyProxies implements KeyedMapping
{
    use KeyRequiring;

    /** @var string */
    private $name;
    /** @var string */
    private $key;
    /** @var Deserializer */
    private $collection;
    /** @var ProxyFactory */
    private $proxyFactory;

    private function __construct(
        string $name,
        string $dataKey,
        Deserializer $collection,
        ProxyFactory $proxyFactory
    ) {
        $this->name = $name;
        $this->key = $dataKey;
        $this->collection = $collection;
        $this->proxyFactory = $proxyFactory;
    }

    /**
     * Creates a new lazily loaded has-many mapping.
     *
     * @param string       $name         The name of both the key and the property.
     * @param Deserializer $collection   The deserializer for the collection.
     * @param ProxyFactory $proxyFactory The proxy factory.
     * @return KeyedMapping              The lazy has-many mapping.
     */
    public static function inProperty(
        string $name,
        Deserializer $collection,
        ProxyFactory $proxyFactory
    ): KeyedMapping {
        return new self($name, $name, $collection, $proxyFactory);
    }

    /**
     * Creates a new lazily loading has-many mapping, using the data from a
     * specific key.
     *
     * @param string       $name         The name of the property.
     * @param string       $key          The array key to use.
     * @param Deserializer $collection   The deserializer for the collection.
     * @param ProxyFactory $proxyFactory The proxy factory.
     * @return KeyedMapping              The lazy has-many mapping.
     */
    public static function inPropertyWithDifferentKey(
        string $name,
        string $key,
        Deserializer $collection,
        ProxyFactory $proxyFactory
    ): KeyedMapping {
        return new self($name, $key, $collection, $proxyFactory);
    }

    /** @inheritdoc */
    public function name(): string
    {
        return $this->name;
    }

    /** @inheritdoc */
    public function key(): string
    {
        return $this->key;
    }

    /** @inheritdoc */
    public function value(array $data, $owner = null)
    {
        $this->mustHaveTheKeyInThe($data);
        $amount = $data[$this->key()];
        try {
            $proxies = $this->makeSomeProxies($amount, $owner);
        } catch (Throwable $exception) {
            throw ProxyProductionFailed::tryingToProduceFor($this, $exception);
        }
        try {
            return $this->collection->from($proxies);
        } catch (Throwable $exception) {
            throw CollectionMappingFailed::forCollection($this, $exception);
        }
    }

    /**
     * Produces the proxies for in the collection.
     *
     * @param int         $amount The amount of proxies to produce.
     * @param object|null $owner  The object that holds a reference to the proxy.
     * @return array              List of proxy objects.
     * @throws ProxyException
     */
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

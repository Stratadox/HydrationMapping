<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Relationship;

use Stratadox\Deserializer\DeserializesCollections;
use Stratadox\HydrationMapping\ExposesDataKey;
use Stratadox\Proxy\ProducesProxies;
use Throwable;

/**
 * Maps a number to a collection of proxies in an object property.
 *
 * @package Stratadox\Hydrate
 * @author  Stratadox
 */
final class HasManyProxies implements ExposesDataKey
{
    use KeyRequiring;

    private $name;
    private $key;
    private $collection;
    private $proxyBuilder;

    private function __construct(
        string $name,
        string $dataKey,
        DeserializesCollections $collection,
        ProducesProxies $proxyBuilder
    ) {
        $this->name = $name;
        $this->key = $dataKey;
        $this->collection = $collection;
        $this->proxyBuilder = $proxyBuilder;
    }

    /**
     * Creates a new lazily loaded has-many mapping.
     *
     * @param string $name                          The name of both the key and
     *                                              the property.
     * @param DeserializesCollections $collection   The deserializer for the
     *                                              collection.
     * @param ProducesProxies         $proxyBuilder The proxy builder.
     * @return ExposesDataKey                       The lazy has-many mapping.
     */
    public static function inProperty(
        string $name,
        DeserializesCollections $collection,
        ProducesProxies $proxyBuilder
    ): ExposesDataKey {
        return new self($name, $name, $collection, $proxyBuilder);
    }

    /**
     * Creates a new lazily loading has-many mapping, using the data from a
     * specific key.
     *
     * @param string                  $name         The name of the property.
     * @param string                  $key          The array key to use.
     * @param DeserializesCollections $collection   The deserializer for the
     *                                              collection.
     * @param ProducesProxies         $proxyBuilder The proxy builder.
     * @return ExposesDataKey                       The lazy has-many mapping.
     */
    public static function inPropertyWithDifferentKey(
        string $name,
        string $key,
        DeserializesCollections $collection,
        ProducesProxies $proxyBuilder
    ): ExposesDataKey {
        return new self($name, $key, $collection, $proxyBuilder);
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
     */
    private function makeSomeProxies(int $amount, ?object $owner): array
    {
        $proxies = [];
        for ($i = 0; $i < $amount; ++$i) {
            $proxies[] = $this->proxyBuilder->createFor($owner, $this->name(), $i);
        }
        return $proxies;
    }
}

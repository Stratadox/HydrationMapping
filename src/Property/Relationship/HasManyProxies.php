<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Relationship;

use Stratadox\Hydration\Mapping\Property\MissingTheKey;
use Stratadox\HydrationMapping\ExposesDataKey;
use Stratadox\Hydrator\Hydrates;
use Stratadox\Proxy\ProducesProxies;
use Throwable;

/**
 * Maps a number to a collection of proxies in an object property.
 *
 * @package Stratadox\Hydrate
 * @author Stratadox
 */
final class HasManyProxies implements ExposesDataKey
{
    private $name;
    private $key;
    private $collection;
    private $proxyBuilder;

    private function __construct(
        string $name,
        string $dataKey,
        Hydrates $collectionHydrator,
        ProducesProxies $proxyBuilder
    ) {
        $this->name = $name;
        $this->key = $dataKey;
        $this->collection = $collectionHydrator;
        $this->proxyBuilder = $proxyBuilder;
    }

    /**
     * Create a new lazily loaded has-many mapping.
     *
     * @param string          $name         The name of both the key and the property.
     * @param Hydrates        $collection   The hydrator for the collection.
     * @param ProducesProxies $proxyBuilder The proxy builder.
     * @return self                         The lazy has-many mapping.
     */
    public static function inProperty(
        string $name,
        Hydrates $collection,
        ProducesProxies $proxyBuilder
    ): self {
        return new self($name, $name, $collection, $proxyBuilder);
    }

    /**
     * Create a new lazily loading has-many mapping, using the data from a
     * specific key.
     *
     * @param string          $name         The name of both the key and the property.
     * @param string          $key          The array key to use.
     * @param Hydrates        $collection   The hydrator for the collection.
     * @param ProducesProxies $proxyBuilder The proxy builder.
     * @return self                         The lazy has-many mapping.
     */
    public static function inPropertyWithDifferentKey(
        string $name,
        string $key,
        Hydrates $collection,
        ProducesProxies $proxyBuilder
    ): self {
        return new self($name, $key, $collection, $proxyBuilder);
    }

    public function name() : string
    {
        return $this->name;
    }

    public function key() : string
    {
        return $this->key;
    }

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
            return $this->collection->fromArray($proxies);
        } catch (Throwable $exception) {
            throw CollectionMappingFailed::tryingToMapCollection($this, $exception);
        }
    }

    private function makeSomeProxies(int $amount, $owner): array
    {
        $proxies = [];
        for ($i = 0; $i < $amount; ++$i) {
            $proxies[] = $this->proxyBuilder->createFor($owner, $this->name(), $i);
        }
        return $proxies;
    }

    private function mustHaveTheKeyInThe(array $data): void
    {
        if (!array_key_exists($this->key(), $data)) {
            throw MissingTheKey::inTheInput($data, $this, $this->key());
        }
    }
}

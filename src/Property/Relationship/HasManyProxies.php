<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Relationship;

use Stratadox\Hydration\Hydrates;
use Stratadox\Hydration\Mapping\Property\FromSingleKey;
use Stratadox\Hydration\MapsProperty;
use Stratadox\Hydration\ProducesProxies;
use Throwable;

/**
 * Maps a number to a collection of proxies in an object property.
 *
 * @package Stratadox\Hydrate
 * @author Stratadox
 */
class HasManyProxies extends FromSingleKey
{
    private $collection;
    private $proxyBuilder;

    protected function __construct(
        string $name,
        string $dataKey,
        Hydrates $collectionHydrator,
        ProducesProxies $proxyBuilder
    ) {
        parent::__construct($name, $dataKey);
        $this->collection = $collectionHydrator;
        $this->proxyBuilder = $proxyBuilder;
    }

    public static function inProperty(
        string $name,
        Hydrates $collection,
        ProducesProxies $proxyBuilder
    ) : MapsProperty
    {
        return new static($name, $name, $collection, $proxyBuilder);
    }

    public static function inPropertyWithDifferentKey(
        string $name,
        string $key,
        Hydrates $collection,
        ProducesProxies $proxyBuilder
    ) : MapsProperty
    {
        return new static($name, $key, $collection, $proxyBuilder);
    }

    /** @inheritdoc @return mixed|object */
    public function value(array $data, $owner = null)
    {
        $amount = $this->my($data);
        try {
            $proxies = [];
            for ($i = 0; $i < $amount; ++$i) {
                $proxies[] = $this->proxyBuilder->createFor($owner, $this->name(), $i);
            }
        } catch (Throwable $exception) {
            throw ProxyProductionFailed::tryingToProduceFor($this, $exception);
        }
        try {
            return $this->collection->fromArray($proxies);
        } catch (Throwable $exception) {
            throw CollectionMappingFailed::tryingToMapCollection(
                $this, $exception, $this->name()
            );
        }
    }
}

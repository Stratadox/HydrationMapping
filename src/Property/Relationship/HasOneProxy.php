<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Relationship;

use Stratadox\Hydration\MapsProperty;
use Stratadox\Hydration\ProducesProxies;
use Throwable;

/**
 * Maps a proxy to a has-one relation in an object property.
 *
 * @package Stratadox\Hydrate
 * @author Stratadox
 */
class HasOneProxy implements MapsProperty
{
    private $name;
    private $proxyBuilder;

    protected function __construct(
        string $name,
        ProducesProxies $proxyBuilder
    ) {
        $this->name = $name;
        $this->proxyBuilder = $proxyBuilder;
    }

    public static function inProperty(
        string $name,
        ProducesProxies $proxyBuilder
    ) : MapsProperty
    {
        return new static($name, $proxyBuilder);
    }

    /** @inheritdoc @return mixed|object */
    public function value(array $data, $owner = null)
    {
        try {
            return $this->proxyBuilder->createFor($owner, $this->name);
        } catch (Throwable $exception) {
            throw ProxyProductionFailed::tryingToProduceFor($this, $exception);
        }
    }

    /** @inheritdoc */
    public function name() : string
    {
        return $this->name;
    }
}

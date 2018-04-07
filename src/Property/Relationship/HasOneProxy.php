<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Relationship;

use Stratadox\HydrationMapping\MapsProperty;
use Stratadox\Proxy\ProducesProxies;
use Throwable;

/**
 * Maps a proxy to a has-one relation in an object property.
 *
 * @package Stratadox\Hydrate
 * @author  Stratadox
 */
final class HasOneProxy implements MapsProperty
{
    private $name;
    private $proxyBuilder;

    private function __construct(string $name, ProducesProxies $proxyBuilder)
    {
        $this->name = $name;
        $this->proxyBuilder = $proxyBuilder;
    }

    /**
     * Creates a new lazily loading has-one mapping.
     *
     * @param string          $name         The name of the property.
     * @param ProducesProxies $proxyBuilder The proxy builder.
     * @return self                         The lazy has-one mapping.
     */
    public static function inProperty(
        string $name,
        ProducesProxies $proxyBuilder
    ): self {
        return new self($name, $proxyBuilder);
    }

    /** @inheritdoc */
    public function value(array $data, $owner = null)
    {
        try {
            return $this->proxyBuilder->createFor($owner, $this->name);
        } catch (Throwable $exception) {
            throw ProxyProductionFailed::tryingToProduceFor($this, $exception);
        }
    }

    /** @inheritdoc */
    public function name(): string
    {
        return $this->name;
    }
}

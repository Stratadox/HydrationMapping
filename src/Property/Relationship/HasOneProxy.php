<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Relationship;

use Stratadox\HydrationMapping\MapsProperty;
use Stratadox\Proxy\ProxyFactory;
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
    private $proxyFactory;

    private function __construct(string $name, ProxyFactory $proxyFactory)
    {
        $this->name = $name;
        $this->proxyFactory = $proxyFactory;
    }

    /**
     * Creates a new lazily loading has-one mapping.
     *
     * @param string       $name         The name of the property.
     * @param ProxyFactory $proxyFactory The proxy builder.
     * @return MapsProperty              The lazy has-one mapping.
     */
    public static function inProperty(
        string $name,
        ProxyFactory $proxyFactory
    ): MapsProperty {
        return new self($name, $proxyFactory);
    }

    /** @inheritdoc */
    public function value(array $data, $owner = null)
    {
        try {
            return $this->proxyFactory->create([
                'owner' => $owner,
                'property' => $this->name
            ]);
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

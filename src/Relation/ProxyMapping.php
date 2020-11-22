<?php declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Relation;

use Stratadox\HydrationMapping\Mapping;
use Stratadox\Proxy\ProxyFactory;
use Throwable;

final class ProxyMapping implements Mapping
{
    /** @var string */
    private $name;
    /** @var ProxyFactory */
    private $proxyFactory;

    private function __construct(string $name, ProxyFactory $proxyFactory)
    {
        $this->name = $name;
        $this->proxyFactory = $proxyFactory;
    }

    public static function inProperty(
        string $name,
        ProxyFactory $proxyFactory
    ): Mapping {
        return new self($name, $proxyFactory);
    }

    public function name(): string
    {
        return $this->name;
    }

    public function value(array $data, $owner = null)
    {
        try {
            return $this->proxyFactory->create([
                'owner' => $owner,
                'property' => $this->name
            ]);
        } catch (Throwable $exception) {
            throw ProxyMappingFailure::encountered($this, $exception);
        }
    }
}

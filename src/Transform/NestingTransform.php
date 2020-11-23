<?php declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Transform;

use Stratadox\Hydration\Mapping\AssertKey;
use Stratadox\HydrationMapping\Mapping;

final class NestingTransform implements Mapping
{
    /** @var string */
    private $key;
    /** @var Mapping */
    private $mapping;

    private function __construct(string $key, Mapping $mapping)
    {
        $this->key = $key;
        $this->mapping = $mapping;
    }

    public static function inKey(string $key, Mapping $mapping): Mapping
    {
        return new self($key, $mapping);
    }

    public function name(): string
    {
        return $this->mapping->name();
    }

    public function value(array $data, $owner = null)
    {
        AssertKey::exists($this, $data, $this->key);
        return $this->mapping->value($data[$this->key]);
    }
}

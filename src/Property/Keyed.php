<?php declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property;

use Stratadox\HydrationMapping\KeyedMapping;
use Stratadox\HydrationMapping\Mapping;

/**
 * Adapter to make a regular mapping a KeyedMapping
 * @deprecated
 */
final class Keyed implements KeyedMapping
{
    /** @var string */
    private $key;
    /** @var Mapping */
    private $mapping;

    public function __construct(string $key, Mapping $mapping)
    {
        $this->key = $key;
        $this->mapping = $mapping;
    }

    public static function mapping(string $key, Mapping $mapping): KeyedMapping
    {
        return new self($key, $mapping);
    }

    public function key(): string
    {
        return $this->key;
    }

    public function name(): string
    {
        return $this->mapping->name();
    }

    public function value(array $data, $owner = null)
    {
        return $this->mapping->value($data, $owner);
    }
}

<?php declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Transform;

use Stratadox\HydrationMapping\Mapping;
use function array_map;

final class MapTransform implements Mapping
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

    public static function withKey(string $key, Mapping $mapping): Mapping
    {
        return new self($key, $mapping);
    }

    public function name(): string
    {
        return $this->mapping->name();
    }

    public function value(array $data, $owner = null)
    {
        $data = array_map(function ($value): array {
            return [$this->key => $value];
        }, $data);
        return $this->mapping->value($data, $owner);
    }
}

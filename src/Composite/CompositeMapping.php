<?php declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Composite;

use Stratadox\HydrationMapping\Mapping;
use Stratadox\HydrationMapping\MappingFailure;
use function assert;

final class CompositeMapping implements Mapping
{
    /** @var Mapping */
    private $mapping;
    /** @var Mapping */
    private $alternative;

    private function __construct(
        Mapping $mapping,
        Mapping $alternative
    ) {
        $this->mapping = $mapping;
        $this->alternative = $alternative;
        assert($this->mapping->name() === $this->alternative->name());
    }

    public static function either(
        Mapping $mapping,
        Mapping $alternative
    ): Mapping {
        return new self($mapping, $alternative);
    }

    public function name(): string
    {
        return $this->mapping->name();
    }

    public function value(array $data, $owner = null)
    {
        try {
            return $this->mapping->value($data, $owner);
        } catch (MappingFailure $firstFailure) {
            try {
                return $this->alternative->value($data, $owner);
            } catch (MappingFailure $secondFailure) {
                throw CompositeMappingFailure::both($firstFailure, $secondFailure);
            }
        }
    }
}

<?php declare(strict_types=1);

namespace Stratadox\Hydration\Mapping;

use Stratadox\HydrationMapping\Mapping;
use Stratadox\HydrationMapping\MappingFailure;
use function array_key_exists;

// In php, functions cannot be auto-loaded, hence the static class wrapping
final class AssertKey
{
    /** @throws MappingFailure */
    public static function exists(Mapping $mapping, array $data, string $key): void
    {
        if (!array_key_exists($key, $data)) {
            throw MissingTheKey::inTheInput($data, $mapping, $key);
        }
    }
}

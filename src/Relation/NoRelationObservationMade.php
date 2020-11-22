<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Relation;

use RuntimeException;
use Stratadox\HydrationMapping\MappingFailure;
use function sprintf;

final class NoRelationObservationMade extends RuntimeException implements MappingFailure
{
    public static function forIn(string $property): MappingFailure
    {
        return new self(sprintf(
            'Failed to map the `%s` relationship: no relation target observed.',
            $property
        ));
    }
}

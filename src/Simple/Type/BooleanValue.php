<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Simple\Type;

use Stratadox\Hydration\Mapping\DifferentKey;
use Stratadox\Hydration\Mapping\Primitive\BooleanMapping;
use Stratadox\HydrationMapping\Mapping;

final class BooleanValue
{
    public static function withCustomTruths(
        string $name,
        array $truths,
        array $falsehoods
    ): Mapping {
        return BooleanMapping::custom($name, $truths, $falsehoods);
    }

    public static function withCustomTruthsAndKey(
        string $name,
        string $key,
        array $truths,
        array $falsehoods
    ): Mapping {
        return DifferentKey::use(
            $key,
            BooleanMapping::custom($name, $truths, $falsehoods)
        );
    }

    public static function inProperty(string $name): Mapping
    {
        return BooleanMapping::inProperty($name);
    }

    public static function inPropertyWithDifferentKey(
        string $name,
        string $key
    ): Mapping {
        return DifferentKey::use($key, BooleanMapping::inProperty($name));
    }
}

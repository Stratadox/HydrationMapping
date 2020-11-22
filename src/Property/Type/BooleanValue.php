<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Type;

use Stratadox\Hydration\Mapping\DifferentKey;
use Stratadox\Hydration\Mapping\Property\Keyed;
use Stratadox\Hydration\Mapping\Primitive\BooleanMapping;
use Stratadox\HydrationMapping\KeyedMapping;

final class BooleanValue
{
    public static function withCustomTruths(
        string $name,
        array $truths,
        array $falsehoods
    ): KeyedMapping {
        return Keyed::mapping($name,
            BooleanMapping::custom($name, $truths, $falsehoods)
        );
    }

    public static function withCustomTruthsAndKey(
        string $name,
        string $key,
        array $truths,
        array $falsehoods
    ): KeyedMapping {
        return Keyed::mapping($key, DifferentKey::use(
            $key,
            BooleanMapping::custom($name, $truths, $falsehoods)
        ));
    }

    public static function inProperty(string $name): KeyedMapping
    {
        return Keyed::mapping($name, BooleanMapping::inProperty($name));
    }

    public static function inPropertyWithDifferentKey(
        string $name,
        string $key
    ): KeyedMapping {
        return Keyed::mapping($key, DifferentKey::use(
            $key,
            BooleanMapping::inProperty($name))
        );
    }
}

<?php

declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test;

use PHPUnit\Framework\TestCase;
use Stratadox\Hydration\Mapping\Property\Scalar\StringValue;

/**
 * @covers \Stratadox\Hydration\Mapping\Property\Scalar\Scalar
 * @covers \Stratadox\Hydration\Mapping\Property\FromSingleKey
 */
class Scalars_can_map_keys_to_properties extends TestCase
{
    /** @scenario */
    function mapping_a_property_with_a_different_array_key()
    {
        $map = StringValue::inPropertyWithDifferentKey('property', 'key');

        $this->assertEquals('property', $map->name());
        $this->assertEquals('value', $map->value(['key' => 'value']));
    }
}

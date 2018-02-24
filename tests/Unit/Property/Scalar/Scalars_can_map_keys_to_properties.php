<?php

declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Unit\Property\Scalar;

use PHPUnit\Framework\TestCase;
use Stratadox\Hydration\Mapping\Property\Scalar\StringValue;
use Stratadox\HydrationMapping\UnmappableInput;

/**
 * @covers \Stratadox\Hydration\Mapping\Property\Scalar\Scalar
 * @covers \Stratadox\Hydration\Mapping\Property\MissingTheKey
 */
class Scalars_can_map_keys_to_properties extends TestCase
{
    /** @test */
    function mapping_a_property_with_a_different_array_key()
    {
        $map = StringValue::inPropertyWithDifferentKey('property', 'key');

        $this->assertEquals('property', $map->name());
        $this->assertEquals('value', $map->value(['key' => 'value']));
    }

    /** @test */
    function throwing_an_exception_if_the_key_does_not_exist()
    {
        $map = StringValue::inProperty('missing');

        $this->expectException(UnmappableInput::class);
        $map->value(['foo' => 'bar']);
    }
}
